<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/class/rentproduct.php");


$sql = "SELECT recom_memreserve_type, sns_ok, sns_reserve_type ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$recom_memreserve_type=$row->recom_memreserve_type;
	$sns_ok=$row->sns_ok;
	$sns_reserve_type=$row->sns_reserve_type;
	$arRecomType = explode("",$recom_memreserve_type);
	$arSnsType = explode("",$sns_reserve_type);
}


// 벤더 정보 리스트
$venderList = venderList("vender,id,com_name");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################
$userspec_cnt=5;
$maxfilesize="2097152";
$mode=$_POST["mode"];

$code=$_POST["code"];
$prcode=$_POST["prcode"];
$productcode=$_POST["productcode"];
$productname=$_POST["productname"];
$prmsg=$_POST["prmsg"];
$productdisprice=$_POST["productdisprice"];
$vimage=$_POST["vimage"];
$vimage2=$_POST["vimage2"];
$vimage3=$_POST["vimage3"];
$attechwide=!_empty($_POST['attechwide'])?trim($_POST['attechwide']):"";

// 정산 기준, 적립금, 쿠폰 사용여부 조회 jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];
$reserve_use = $shop_more_info['reserve_use'];
$coupon_use = $shop_more_info['coupon_use'];

// 추천인 적립금 사용 유무
$reseller_reserve_no_use = $_POST['reseller_reserve_no_use'];
// 추천인 적립금 사용 유무

// 배송수단 선택
$deli_type = $_POST['deli_type'];
if (is_array($deli_type)) {
	$deli_type = implode(',', $deli_type);
}
// 배송수단 선택

$adjust_title = "판매 개별 수수료";
if($account_rule) $adjust_title = "개별 공급가";


$busySeason=$_POST["busySeason"];
$semiBusySeason=$_POST["semiBusySeason"];
$holidaySeason=$_POST["holidaySeason"];

//당일예약여부
$today_reserve=!_empty($_POST['today_reserve'])?trim($_POST['today_reserve']):"N";


// 정산 기준, 적립금, 쿠폰 사용여부 조회 jdy
$syncNaverEp = '1';
if(strlen($code)==12) {
	$sql = "SELECT syncNaverEp,type, list_type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
	$sql.= "AND codeB='".substr($code,3,3)."' ";
	$sql.= "AND codeC='".substr($code,6,3)."' AND codeD='".substr($code,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) exit;
	if(!ereg("X",$row->type)) exit;

	$type = $row->type;
	if (substr($row->list_type,0,1)=="B")
		$gongtype="Y";
	else
		$gongtype="N";

	if($gongtype=="Y") {
		$bgcolor="#E9E7F1";
		$list_title = "<B>가격 고정형 공동구매 상품목록</B>";
	} else {
		$bgcolor="#F0F0F0";
		$list_title = "<B>판매상품 목록</B>";
	}

	$syncNaverEp = $row->syncNaverEp;

} else {
	exit;
}


if(strlen($_POST["setcolor"])==0){
	$setcolor=$_COOKIE["setcolor"];
} else if($_COOKIE["setcolor"]!=$_POST["setcolor"]){
	setcookie("setcolor",$setcolor,0,"/".RootPath.AdminDir);
	$setcolor=$_POST["setcolor"];
} else {
	$setcolor=$_COOKIE["setcolor"];
}

#상품이미지 태두리 쿠키 설정
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	setcookie("imgborder","Y",0,"/".RootPath.AdminDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && ($mode=="insert" || $mode=="modify")) {
	setcookie("imgborder","",time()-3600,"/".RootPath.AdminDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}

#상품등록날짜 고정 쿠키 설정
if ($_COOKIE["insertdate_cook"]=="Y" && $insertdate!="Y" && $mode=="modify") {
	setcookie("insertdate_cook","",time()-3600,"/".RootPath.AdminDir);
	$insertdate_cook="";
} else if ($_COOKIE["insertdate_cook"]!="Y" && $insertdate=="Y" && $mode=="modify") {
	setcookie("insertdate_cook","Y",time()+2592000,"/".RootPath.AdminDir);
	$insertdate_cook="Y";
}

if(strlen($setcolor)==0) $setcolor="000000";
$rcolor=HexDec(substr($setcolor,0,2));
$gcolor=HexDec(substr($setcolor,2,2));
$bcolor=HexDec(substr($setcolor,4,2));
$quality = "90";


$popup=$_POST["popup"];
$option1=$_POST["option1"];
$option1_name=$_POST["option1_name"];
$option2=$_POST["option2"];
$option2_name=$_POST["option2_name"];
$consumerprice=$_POST["consumerprice"];
$discountRate=$_POST["discountRate"];
$buyprice=$_POST["buyprice"];
$sellprice=$_POST["sellprice"];
$assembleuse=$_POST["assembleuse"];
$production=$_POST["production"];
$keyword=$_POST["keyword"];
$quantity=$_POST["quantity"];
$checkquantity=$_POST["checkquantity"];
$reserve=$_POST["reserve"];
$reservetype=$_POST["reservetype"];
$package_num=$_POST["package_num"];
$deli=$_POST["deli"];

if($deli=="Y")
	$deli_price=(int)$_POST["deli_price_value2"];
else
	$deli_price=(int)$_POST["deli_price_value1"];

if($deli=="H" || $deli=="F" || $deli=="G") $deli_price=0;
if($deli!="Y" && $deli!="F" && $deli!="G") $deli="N";

// 노출 여부 수정 jdy
$display=$_POST["display"];
$tax_yn = $_POST["tax_yn"];
// 노출 여부 수정 jdy

$addcode=$_POST["addcode"];
$option_price=ereg_replace(" ","",$_POST["option_price"]);
$option_price=substr($option_price,0,-1);
$madein=$_POST["madein"];
$model=$_POST["model"];
$brandname=$_POST["brandname"];
$opendate=$_POST["opendate"];
$selfcode=$_POST["selfcode"];
$bisinesscode=$_POST["bisinesscode"];
$optiongroup=$_POST["optiongroup"];
$imgcheck=$_POST["imgcheck"];
$bankonly=$_POST["bankonly"];
$deliinfono=$_POST["deliinfono"];
$setquota=$_POST["setquota"];
$miniq=$_POST["miniq"];
$maxq=$_POST["maxq"];
$insertdate=$_POST["insertdate"];
$localsave=$_POST["localsave"];
$content=$_POST["content"];
$dicker=$_POST["dicker"];
$dicker_text=$_POST["dicker_text"];

$userspec=$_POST["userspec"];
$specname=$_POST["specname"];
$specvalue=$_POST["specvalue"];

//예약 판매 상품 정보
$reservation = ( $_POST["reservation"] == "Y" AND strlen($_POST["reservationDate"]) > 0 ) ? $_POST["reservationDate"] : '' ;

$booking_confirm=$_POST["booking_confirm"]=="now"?$_POST["booking_confirm"]:$_POST["booking_confirm_time"];

$group_check=$_POST["group_check"];
//$group_code=$_POST["group_code"];

$group_sel_code=$_POST["group_sel_code"];

if($group_check=="Y" && count($group_sel_code)>0) {
	$group_check="Y";
} else {
	$group_check="N";
	$group_sel_code ="";
}

$gonggu_product = $_POST["gonggu_product"];

$sns_state=$_POST["sns_state"];
//$present_state=$_POST["present_state"];
$present_state='N';
$pester_state='N';
//$pester_state=$_POST["pester_state"];
if($sns_state =="Y" && $arSnsType[0] =="B"){
	$sns_reserve1=$_POST["sns_reserve1"];
	$sns_reserve1_type=$_POST["sns_reserve1_type"];
	$sns_reserve2=$_POST["sns_reserve2"];
	$sns_reserve2_type=$_POST["sns_reserve2_type"];
}else{
	$sns_reserve1="";
	$sns_reserve1_type="";
	$sns_reserve2="";
	$sns_reserve2_type="";
}
$first_reserve=$_POST["first_reserve"];
$first_reserve_type=$_POST["first_reserve_type"];

unset($specarray);
if($userspec == "Y") {
	for($i=0; $i<$userspec_cnt; $i++) {
		$specarray[$i]=$specname[$i]."".$specvalue[$i];
	}
	$userspec = implode("=",$specarray);
} else {
	$userspec = "";
}

if(strlen($display)==0) $display='Y';

if((int)$opendate<1) $opendate="";

$searchtype=$_POST["searchtype"];
if(strlen($searchtype)==0) $searchtype=0;

$etcapply_coupon=$_POST["etcapply_coupon"];
$etcapply_reserve=$_POST["etcapply_reserve"];
$etcapply_gift=$_POST["etcapply_gift"];
$etcapply_return=$_POST["etcapply_return"];
if($etcapply_coupon!="Y") $etcapply_coupon="N";
if($etcapply_reserve!="Y") $etcapply_reserve="N";
if($etcapply_gift!="Y") $etcapply_gift="N";
if($etcapply_return!="Y") $etcapply_return="N";

// 리셀러 적립금 관련 추가
$reseller_reserve = '-1';
/*
if(!_empty($_POST['reseller_reservetype']) && !_isInt($_POST['reseller_reserve'])){
	if($_POST['reseller_reservetype'] == '100') $reseller_reserve = floatval($_POST['reseller_reserve']/100);		
	else $reseller_reserve = intval($_POST['reseller_reserve']/100);
}*/
if(_isInt($_POST['reseller_reserve'])) $reseller_reserve = floatval($_POST['reseller_reserve']/100);		
else if($_POST['reseller_reserve'] == '0') $reseller_reserve = 0;

$userfile = $_FILES["userfile"];
$userfile2 = $_FILES["userfile2"];
$userfile3 = $_FILES["userfile3"];

$use_imgurl=$_POST["use_imgurl"];
$userfile_url=$_POST["userfile_url"];
$userfile2_url=$_POST["userfile2_url"];
$userfile3_url=$_POST["userfile3_url"];
if($use_imgurl!="Y") {
	$userfile_url="";
	$userfile2_url="";
	$userfile3_url="";
}

$maxsize=130;
$makesize=130;

$card_splittype = $_shopdata->card_splittype;
$makesize=$_shopdata->primg_minisize;
$predit_type=$_shopdata->predit_type;
$maxsize=$makesize+10;
if(strpos(" ".$_shopdata->etctype,"IMGSERO=Y")) {
	$imgsero="Y";
}

if(substr($prcode,0,12)!=$code && strlen($prcode)>0) {
	$prcode="";
	$maxq="";
}
if (strlen($mode)==0) $maxq="";



################################상품별 회원할인율######################################
$group_code=(array)$_POST["group_code"];
$discount=(array)$_POST["discount"];
$discount_type=(array)$_POST["discount_type"];
$over_discount=$_POST["over_discount"];
$discountYN=$_POST["discountYN"];



if ($mode=="insert" || $mode=="modify") {
	$etctype = "";
	if ($bankonly=="Y") $etctype .= "BANKONLY";
	if ($deliinfono=="Y") $etctype .= "DELIINFONO=Y";
	if ($setquota=="Y") $etctype .= "SETQUOTA";
	if (strlen(substr($iconvalue,0,3))>0)	   $etctype .= "ICON=".$iconvalue."";
	if ($dicker=="Y" && strlen($dicker_text)>0) $etctype .= "DICKER=".$dicker_text."";

	if ($miniq>1)	   $etctype .= "MINIQ=".$miniq."";
	else if ($miniq<1){
		echo "<script>alert('최소구매수량 수량은 1개 보다 커야 합니다.');history.go(-1);</script>";exit;
	}
	if ($checkmaxq=="B" && $maxq>=1)		$etctype .= "MAXQ=".$maxq."";
	else if ($checkmaxq=="B" && $maxq<1){
		echo "<script>alert('최대구매수량 수량은 1개 보다 커야 합니다.');history.go(-1);</script>";exit;
	}

	if ($bankonly=="Y" && $setquota=="Y") {
		echo "<script>alert('현금전용결제와 무이자상점부담을 동시에 체크하실 수 없습니다.(결제수단이 서로 틀림)');";
		echo "history.go(-1);</script>";exit;
	}
} else {
	$bankonly="";
	$deliinfono="";
	$setquota="";
	$miniq="";
	$freedeli="N";
}

$imagepath=$Dir.DataDir."shopimages/product/";

if($mode=="delprdtimg"){
	$delarray = array (&$vimage,&$vimage2,&$vimage3,&$attechwide);
	$delname = array ("maximage","minimage","tinyimage","wideimage");

	if(strlen($attechwide)>0 && $delprdtimg == "3") {
		$imagepath=$Dir.DataDir."shopimages/wideimage/";
	}
	if(strlen($delarray[$delprdtimg])>0 && file_exists($imagepath.$delarray[$delprdtimg])) {
		unlink($imagepath.$delarray[$delprdtimg]);
	}

	$sql = "UPDATE tblproduct SET $delname='' WHERE productcode = '".$prcode."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('해당 상품이미지를 삭제하였습니다.');</script>";
}

if (strlen($option1)>0 && strlen($option1_name)>0) {
	$option1 = $option1_name.",".substr($option1,0,-1);
} else {
	$option1="";
}
if (strlen($option2)>0 && strlen($option2_name)>0) {
	$option2 = $option2_name.",".substr($option2,0,-1);
} else {
	$option2="";
}

$optcnt="";
$tempcnt=0;
if ($searchtype=="1") {
	for($i=0;$i<10;$i++){
		for($j=0;$j<10;$j++){
			if(strlen(trim($optnumvalue[$i][$j]))>0) {
				$optnumvalue[$i][$j]=(int)$optnumvalue[$i][$j];
				$tempcnt++;
			}
			$optcnt.=",".$optnumvalue[$i][$j];
		}
	}
}
if($tempcnt>0) $optcnt.=",";
else $optcnt="";


// 렌탈용 옵션 정리
// 렌탈 옵션 처리
$productoptions = array();
if($_REQUEST['goodsType'] == '2'){
	$optquantity = 0;
	for($oi = 0;$oi < count($_REQUEST['optionName']);$oi++){
		$tmpopt = array();
		$tmpopt['idx'] =_isInt($_REQUEST['roptidx'][$oi])?$_REQUEST['roptidx'][$oi]:'';
		$tmpopt['grade'] = $_REQUEST['optionGrade'][$oi];
		if($pricetype!="long"){
			$tmpopt['optionName'] = ($_REQUEST['multiOpt'] == '0')?'단일가격':$_REQUEST['optionName'][$oi];
		}else{
			$tmpopt['optionName'] = $_REQUEST['optionName'][$oi];
		}
		$tmpopt['custPrice'] = _isInt($_REQUEST['custPrice'][$oi])?$_REQUEST['custPrice'][$oi]:0;
		$tmpopt['priceDiscP'] = _isInt($_REQUEST['priceDiscP'][$oi])?$_REQUEST['priceDiscP'][$oi]:0;
		$tmpopt['nomalPrice'] = $_REQUEST['nomalPrice'][$oi];
		$tmpopt['productCount'] = $_REQUEST['productCount'][$oi];

		//초과시간관련
		$tmpopt['productTimeover_percent'] = $_REQUEST['productTimeover_percent'][$oi];
		$tmpopt['productTimeover_price'] = $_REQUEST['productTimeover_price'][$oi];
		$tmpopt['productHalfday_percent'] = $_REQUEST['productHalfday_percent'][$oi];
		$tmpopt['productHalfday_price'] = $_REQUEST['productHalfday_price'][$oi];
		$tmpopt['productOverHalfTime_percent'] = $_REQUEST['productOverHalfTime_percent'][$oi];
		$tmpopt['productOverHalfTime_price'] = $_REQUEST['productOverHalfTime_price'][$oi];
		$tmpopt['productOverOneTime_percent'] = $_REQUEST['productOverOneTime_percent'][$oi];
		$tmpopt['productOverOneTime_price'] = $_REQUEST['productOverOneTime_price'][$oi];

		// 160105 대여 상품이 옵션 별로 여러 개 일 경우 옵션 별 갯수 합계.
		$optquantity = $optquantity + $tmpopt['productCount'];

		$tmpopt['optionPay'] = $_REQUEST['optionPay'][$oi];
		$tmpopt['deposit'] = $_REQUEST['deposit'][$oi];
		$tmpopt['prepay'] = $_REQUEST['prepay'][$oi];
		
		$tmpopt['busySeason'] = _isInt($_REQUEST['busySeason'][$oi])?$_REQUEST['busySeason'][$oi]:0;
		$tmpopt['busyHolidaySeason'] = _isInt($_REQUEST['busyHolidaySeason'][$oi])?$_REQUEST['busyHolidaySeason'][$oi]:0;
		$tmpopt['semiBusySeason'] = _isInt($_REQUEST['semiBusySeason'][$oi])?$_REQUEST['semiBusySeason'][$oi]:0;
		$tmpopt['semiBusyHolidaySeason'] = _isInt($_REQUEST['semiBusyHolidaySeason'][$oi])?$_REQUEST['semiBusyHolidaySeason'][$oi]:0;
		$tmpopt['holidaySeason'] = _isInt($_REQUEST['holidaySeason'][$oi])?$_REQUEST['holidaySeason'][$oi]:0;
		
		array_push($productoptions,$tmpopt);
		if($_REQUEST['multiOpt'] == '0') break;
	}
	
	// 가격 정보 조정
	if($_REQUEST['goodsType'] == '2' && _array($productoptions)){
		$checkquantity = 'C';
		$sellprice = $productoptions[0]['nomalPrice'];
		$consumerprice = $productoptions[0]['custPrice'];
		$discountRate = $productoptions[0]['priceDiscP'];
		$quantity = $optquantity;
	}
	
}

if($mode=="insert") {
	$sql = "SELECT MAX(productcode) as maxproductcode FROM tblproduct ";
	$sql.= "WHERE productcode LIKE '".$code."%' ";
	$result = mysql_query($sql,get_db_conn());
	if ($rows = mysql_fetch_object($result)) {
		if (strlen($rows->maxproductcode)==18) {
			$productcode = ((int)substr($rows->maxproductcode,12))+1;
			$productcode = sprintf("%06d",$productcode);
		} else if($rows->maxproductcode==NULL){
			$productcode = "000001";
		} else {
			echo "<script>alert('상품코드를 생성하는데 실패했습니다. 잠시후 다시 시도하세요.');";
			echo "history.go(-1);</script>";
			exit;
		}
		mysql_free_result($result);
	} else {
		$productcode = "000001";
	}
}

if ($mode=="insert") {
	$image_name = $code.$productcode;
} elseif ($mode=="modify") {
	$image_name = $prcode;
}

if($use_imgurl!="Y") {
	$file_size = $userfile[size]+$userfile2[size]+$userfile3[size];
} else {
	$file_size=0;
}

if($file_size < $maxfilesize) {
	if (strlen($reserve)==0) {
		$reserve=0;
	} else {
		$reserve=$reserve*1;
	}

	if ($reservetype!="Y") {
		$reservetype=="N";
	}

	$curdate = date("YmdHis");

	$productname = ereg_replace("\\\\'","''",$productname);
	$prmsg = ereg_replace("\\\\'","''",$prmsg);
	$addcode = ereg_replace("\\\\'","''",$addcode);
	$content = ereg_replace("\\\\'","''",$content);

	$message="";

	if($use_imgurl!="Y") {
		if($imgcheck=="Y") $filename = array (&$userfile[name],&$userfile[name],&$userfile[name]);
		else $filename = array (&$userfile[name],&$userfile2[name],&$userfile3[name]);
		$file = array (&$userfile[tmp_name],&$userfile2[tmp_name],&$userfile3[tmp_name]);
	} else {
		if($imgcheck=="Y") $filename = array (&$userfile_url,&$userfile_url,&$userfile_url);
		else $filename = array (&$userfile_url,&$userfile2_url,&$userfile3_url);
		$file = array (&$userfile_url,&$userfile2_url,&$userfile3_url);
	}

	$vimagear = array (&$vimage,&$vimage2,&$vimage3);
	$imgnum = array ("","2","3");

	if($mode=="insert" || $mode=="modify"){
		for($i=0;$i<3;$i++){
			if($use_imgurl!="Y") {
				if ($mode=="modify" && strlen($vimagear[$i])>0 && strlen($filename[$i])>0 && file_exists($imagepath.$vimagear[$i])) {
					unlink($imagepath.$vimagear[$i]);
				}
				if (strlen($filename[$i])>0 && file_exists($file[$i])) {
					$ext = strtolower(substr($filename[$i],strlen($filename[$i])-3,3));
					if ($ext=="gif" || $ext=="jpg") {
						$image[$i] = $image_name.$imgnum[$i].".".$ext;
						move_uploaded_file($file[$i],$imagepath.$image[$i]);
						chmod($imagepath.$image[$i],0664);
					} else {
						$image[$i]="";
					}
				} else if($imgcheck=="Y" && strlen($filename[$i])>0) {
					$image[$i]=$image_name.$imgnum[$i].".".$ext;
					copy($imagepath.$image[0],$imagepath.$image[$i]);
				} else {
					$image[$i] = $vimagear[$i];
				}
			}/* else {
				if(strlen($filename[$i])>0 && strlen($file[$i])>0) {
					$image_url=eregi_replace("http://","",$file[$i]);
					$temp=explode("/",$image_url);
					$host=$temp[0];
					$path=eregi_replace($host,"",$image_url);

					$ext=substr(strrchr($image_url,"."),1);

					$is_upimage=true;
					if($ext=="gif" || $ext=="jpg") {
						$image[$i] = $image_name.$imgnum[$i].".".$ext;
						$fdata=getRemoteImageData($host,$path,$ext);

						if(strlen($fdata)>0) {
							$fp2=fopen($imagepath.$image[$i],"w");
							fputs($fp2,$fdata);
							fclose($fp2);
							chmod($imagepath.$image[$i],0664);
							$tempsize=@getimagesize($imagepath.$image[$i]);
							if($tempsize[0]>0 && $tempsize[1]>0 && (preg_match("/^(1|2)$/",$tempsize[2]))) {

							} else {
								@unlink($imagepath.$image[$i]);
								$is_upimage=false;
							}
						} else {
							$is_upimage=false;
						}
					} else {
						$is_upimage=false;
					}

					if($is_upimage==false) {
						$image[$i]="";
						$filename[$i]="";
					}
				} else if($imgcheck=="Y" && strlen($filename[$i])>0) {
					$image[$i]=$image_name.$imgnum[$i].".".$ext;
					copy($imagepath.$image[0],$imagepath.$image[$i]);
					chmod($imagepath.$image[$i],0664);
				} else {
					$image[$i] = $vimagear[$i];
				}
			}*/
		}

		if ($imgcheck=="Y" && strlen($filename[1])>0 && file_exists($imagepath.$image[1])) {
			$imgname=$imagepath.$image[1];
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize1=330;
			if ($width>$makesize1 || $height>$makesize1) {
				if($imgtype==1)	  $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($width>=$height) {
					$small_width=$makesize1;
					$small_height=($height*$makesize1)/$width;
				} else if($width<$height) {
					$small_width=($width*$makesize1)/$height;
					$small_height=$makesize1;
				}

				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imagePNG($im2,$imgname);
				}

				ImageDestroy($im);
				ImageDestroy($im2);
			}
		}
		if (strlen($filename[2])>0 && file_exists($imagepath.$image[2])) {
			$imgname=$imagepath.$image[2];
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize2=250;
			$changefile="Y";
			if($imgsero=="Y") $leftmax=$makesize2;
			else $leftmax=$maxsize;
			if ($width>$maxsize || $height>$leftmax) {
				if($imgtype==1)	  $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($width>=$height) {
					$small_width=$makesize;
					$small_height=($height*$makesize)/$width;
				} else if ($width<$height) {
					if ($imgsero=="Y") {
						$temwidth=$width;$temheight=$height;
						if ($temwidth>$makesize) {
							$temheight=($temheight*$makesize)/$temwidth;
							$temwidth=$makesize;
						}
						if ($temheight>$makesize2) {
							$temwidth=($temwidth*$makesize2)/$temheight;
							$temheight=$makesize2;
						}
						$small_width=$temwidth; $small_height=$temheight;
					} else {
						$small_width=($width*$makesize)/$height; $small_height=$makesize;
					}
				}

				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF일경우
					// 홀수픽셀의 경우 검은줄을 흰색으로 바꾸기위해.
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					//$color = ImageColorAllocate ($im2, 0, 0, 0);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imagePNG($im2,$imgname);
				}

				ImageDestroy($im);
				ImageDestroy($im2);
			} else if($imgborder=="Y") {
				if($imgtype==1)	  $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($imgtype==1) {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					//$color = ImageColorAllocate ($im, 0, 0, 0);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imageGIF($im,$imgname);
				} else if ($imgtype==2) {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imageJPEG($im,$imgname,$quality);
				} else {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imagePNG($im,$imgname);
				}
				ImageDestroy($im);
			}
		}
		if($checkquantity=="F") $quantity="NULL";
		else if($checkquantity=="E") $quantity=0;
		else if($checkquantity=="A") $quantity=-9999;
		if ($searchtype=="3") {
			if($optiongroup>0) {
				$option1="[OPTG".$optiongroup."]";
				$option2="";
				$option_price="";
				$optcnt="";
			}
		}
	} else if($mode=="delete"){
		for($i=0;$i<3;$i++){
			if(strlen($vimagear[$i])>0 && file_exists($imagepath.$vimagear[$i]))
				unlink($imagepath.$vimagear[$i]);
		}
	}

	####################### 타서버 이미지 쇼핑몰에 저장 #############################
	unset($arrimgurl);
	unset($arrsavefilename);
	$in_content=$content;
	if($mode=="insert" || $mode=="modify") {
		if($localsave=="Y") {
			$imagesavepath=$Dir.DataDir."design/etc/";
			if(is_dir($imagesavepath)==false) {
				mkdir($imagesavepath);
				chmod($imagesavepath,0755);
			}
			$arrimgurl=array();
			$cln=explode("\n", $in_content);
			for($i=0;$i<count($cln);$i++) {
				while(eregi("[^= \"']*\.(gif|jpg|bmp|png)([\"|\'| |>]){1}", $cln[$i], $imgval)){
					$arrimgurl[substr($imgval[0],0,-1)]=substr($imgval[0],0,-1);
					$cln[$i]=str_replace(substr($imgval[0],0,-1),"",$cln[$i]);
				}
			}
			if(count($arrimgurl)>0) {
				while(list($key,$val)=each($arrimgurl)) {
					$file_url=urldecode($val);
					if(substr($file_url,0,7)=="http://") {
						$file_url=eregi_replace("http://","",$file_url);
						$temp=explode("/",$file_url);
						$host=$temp[0];
						$path=eregi_replace($host,"",$file_url);

						$filename=substr(strrchr($file_url,"/"),1);
						$j=0;
						while(file_exists($imagesavepath.$filename)){
							$file_ext=substr( strrchr($filename,"."),1);
							$file_name=substr($filename, 0, strlen($filename) - strlen(strrchr($filename,".")));
							$file_name=substr($file_name, 0, strlen($file_name) - strlen(strrchr($file_name,"[")));

							$filename=$file_name."[".$j."].".$file_ext;
							$j++ ;
						}

						$ext=substr(strrchr($filename,"."),1);

						$fdata=getRemoteImageData($host,$path,$ext);

						if(strlen($fdata)>0) {
							$filepath=$imagesavepath.$filename;
							$fp=@fopen($filepath,"w");
							@fputs($fp,$fdata);
							@fclose($fp);
							@chmod($filepath,0604);

							$size=@getimagesize($filepath);

							if($size[0]>0 && $size[1]>0 && (preg_match("/^(1|2|3|6)$/",$size[2]))) {
								$arrsavefilename[]=$filename;
								$in_content=@str_replace($val,"/".RootPath.DataDir."design/etc/".$filename,$in_content);
							} else {
								@unlink($filepath);
							}
						}
					}
				}
			}
		}
	}

	#와이드 이미지 추가
	$savewideimage = $Dir.DataDir."shopimages/wideimage/";
	if(!_empty($_FILES['wideimage']['name'])){
		$attechfilename=$widefilename="";
		$tempext=$widefileext=array();
		$widesaveloc = $_SERVER['DOCUMENT_ROOT']."/data/shopimages/wideimage/";
		$allowimagefile = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png','image/gif');
		$tempext = pathinfo($_FILES['wideimage']['name']);
		$widefileext = strtolower($tempext['extension']);

		switch($mode){
			case "insert":
			$widefilename = $code.$productcode.".".$widefileext;
			break;
			case "modify";
			$widefilename = $productcode.".".$widefileext;
			break;
		}
		if(is_file($savewideimage.$widefilename) && $mode=="modify"){
			@unlink($savewideimage.$widefilename);
		}

		if(!is_dir($widesaveloc)){
			if(mkdir($widesaveloc)){
				@chmod($widesaveloc, 0707);
			}
		}

		if(in_array($_FILES['wideimage']['type'],$allowimagefile)){
			if($_FILES['wideimage']['size']<=$maxfilesize){
				if(move_uploaded_file($_FILES['wideimage']['tmp_name'],$savewideimage.$widefilename)){
					$attechfilename = $widefilename;
				}
			}
		}
	}

	if ($mode=="insert") {
		if(strlen($buyprice) < 1 ) $buyprice = 0 ;
		$result = mysql_query("SELECT COUNT(*) as cnt FROM tblproduct ",get_db_conn());
		if ($row=mysql_fetch_object($result)) $cnt = $row->cnt;
		else $cnt=0;
		mysql_free_result($result);

		if($assembleuse=="Y") {
			$sellprice=0;
			$option1="";
			$option2="";
			$option_price="";
			$optcnt="";
			$package_num="0";
		}

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$in_content = str_replace('/data/editor_temp/','/data/editor/',$in_content);
		}
		/** #에디터 관련 파일 처리 추가 부분 */
				
		if(!_empty($vender)){
			$vendercode = $vender;
		}else{
			$vendercode = "0000";
		}
		
		if(!_empty($trust_vender)){
			$trust_vendercode = $trust_vender;
		}else{
			$trust_vendercode = "0000";
		}

		$sql = "SELECT MAX(RIGHT(productnumber,6)) as maxproductnumber FROM tblproduct ";
		$sql.= "WHERE productnumber LIKE '".$vendercode."-".$trust_vendercode."-"."%' ";
		$result = mysql_query($sql,get_db_conn());
		if ($rows = mysql_fetch_object($result)) {
			if (strlen($rows->maxproductnumber)==6) {
				$productnumber = ((int)$rows->maxproductnumber)+1;
				$productnumber = sprintf("%06d",$productnumber);
			} else if($rows->maxproductnumber==NULL){
				$productnumber = "000001";
			}
			mysql_free_result($result);
		} else {
			$productnumber = "000001";
		}


		$sql = "INSERT tblproduct SET ";
		$sql.= "productcode		= '".$code.$productcode."', ";
		$sql.= "productnumber	= '".$vendercode."-".$trust_vendercode."-".$productnumber."', ";
		$sql.= "productname		= '".$productname."', ";
		$sql.= "prmsg		= '".$prmsg."', ";
		$sql.= "assembleuse		= '".$assembleuse."', ";
		$sql.= "assembleproduct	= '', ";
		$sql.= "sellprice		= ".$sellprice.", ";
		$sql.= "consumerprice	= ".$consumerprice.", ";
		$sql.= "discountRate	= '".$discountRate."', ";
		$sql.= "buyprice		= ".$buyprice.", ";
		$sql.= "reserve			= '".$reserve."', ";
		$sql.= "reservetype		= '".$reservetype."', ";
		$sql.= "production		= '".$production."', ";
		$sql.= "madein			= '".$madein."', ";
		$sql.= "model			= '".$model."', ";
		$sql.= "opendate		= '".$opendate."', ";
		$sql.= "selfcode		= '".$selfcode."', ";
		$sql.= "bisinesscode	= '".$bisinesscode."', ";
		$sql.= "quantity		= ".$quantity.", ";
		$sql.= "group_check		= '".$group_check."', ";
		$sql.= "keyword			= '".$keyword."', ";
		$sql.= "addcode			= '".$addcode."', ";
		$sql.= "userspec		= '".$userspec."', ";
		$sql.= "maximage		= '".$image[0]."', ";
		$sql.= "minimage		= '".$image[1]."', ";
		$sql.= "tinyimage		= '".$image[2]."', ";
		if(strlen($attechfilename)>0){
			$sql.= "wideimage		= '".$attechfilename."', ";
		}
		$sql.= "option_price	= '".$option_price."', ";
		$sql.= "option_quantity	= '".$optcnt."', ";
		$sql.= "option1			= '".$option1."', ";
		$sql.= "option2			= '".$option2."', ";
		$sql.= "etctype			= '".$etctype."', ";
		// 배송수단 선택 추가
		$sql.= "deli_type		= '".$deli_type."', ";
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		$sql.= "package_num		= '".(int)$package_num."', ";
		$sql.= "reservation		= '".$reservation."', ";

		// insert 시 벤더 처리
		$vender_id = (!_empty($_POST['vender_name'])?preg_replace('(\([^\)]*\))','',$_POST['vender_name']):'');
		$vender = "0";
		if(!_empty($vender_id)){
			$check = "select vender from tblvenderinfo where id = '".$vender_id."' limit 1";
			$rsd = mysql_query($check,get_db_conn());
			if($rsd && mysql_num_rows($rsd) == 1) $vender = mysql_result($rsd,0,0);
		}
		$sql .= "vender		= '".$vender."', ";

		$sql.= "etcapply_coupon	= '".$etcapply_coupon."', ";
		$sql.= "etcapply_reserve= '".$etcapply_reserve."', ";
		$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
		$sql.= "etcapply_return	= '".$etcapply_return."', ";

		$sql.= "display			= '".$display."', ";
		$sql.= "date			= '".$curdate."', ";
		$sql.= "regdate			= now(), ";
		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".$in_content."', ";
		$sql.= "sns_state		= '".$sns_state."', ";
		$sql.= "present_state	= '".$present_state."', ";
		$sql.= "pester_state	= '".$pester_state."', ";
		$sql.= "gonggu_product		= '".$gonggu_product."', ";
		$sql.= "sns_reserve1		= '".$sns_reserve1."', ";
		$sql.= "sns_reserve1_type	= '".$sns_reserve1_type."', ";
		$sql.= "sns_reserve2		= '".$sns_reserve2."', ";
		$sql.= "sns_reserve2_type	= '".$sns_reserve2_type."', ";
		$sql.= "first_reserve		= '".$first_reserve."', ";
		$sql.= "first_reserve_type	= '".$first_reserve_type."', ";


		$sql.= "syncNaverEp	= '".(($_POST['syncNaverEp'] =='0')?'0':'1')."', ";

		$sql.= "productdisprice	= '".$productdisprice."', ";
		$sql.= "tax_yn = '".$tax_yn."', ";
		$sql.= "rental = '".$_POST["goodsType"]."', ";
		$sql.= "today_reserve = '".$today_reserve."', "; //당일예약추가
		$sql.= "reseller_reserve = '".$reseller_reserve."', "; //추가
		$sql.= "reseller_reserve_no_use = '".$reseller_reserve_no_use."', "; //추가

		$sql.= "booking_confirm	= '".$booking_confirm."', ";
		

		//검색 키워드등록 start
		$kw_idx = $_POST["kw_idx"];
		$arrKeyword = "";
		
		for($i=0;$i<sizeof($kw_idx);$i++){
			$kwsql = "SELECT * FROM tblkeyword WHERE productcode = '' AND code='' ";
			$kwsql.= "AND kw_idx = '".$kw_idx[$i]."' ";
			$result=mysql_query($kwsql,get_db_conn());
			$row=mysql_fetch_object($result);
			if($row){
				$ksql = "UPDATE tblkeyword SET ";
				$ksql.= "code	= '".$code."', ";
				$ksql.= "productcode	= '".$code.$productcode."' ";
				$ksql.= "WHERE kw_idx	= '".$kw_idx[$i]."' ";
				mysql_query($ksql,get_db_conn());
			}

			$kw = $_POST[$kw_idx[$i]."_kw"];
			if(sizeof($kw)!=0){
				for($j=0;$j<sizeof($kw);$j++){
					$arrKeyword .= $kw[$j]."||";
				}
			}
		}
		$arrKeyword = substr($arrKeyword,0,-2);

		$sql.= "catekeyword = '".$arrKeyword."' ";
		//검색키워드등록 end

		if($insert = mysql_query($sql,get_db_conn())){
			$pridx = mysql_insert_id(get_db_conn());
			
			// 렌탈 옵션 처리
			if($_REQUEST['goodsType'] == '2'){
				// 대여 상품 저장
				$rentProductValue = array();
				$rentProductValue['pridx'] = $pridx;
				$rentProductValue['istrust'] = $_POST["istrust"];
				$rentProductValue['location'] = $_POST["location"];
				$rentProductValue['goodsType'] = $_POST["goodsType"];
				$rentProductValue['itemType'] = $_POST["itemType"];			
				$rentProductValue['multiOpt'] = ($_REQUEST['multiOpt'] == '1')?'1':'0';
				if($rentProductValue['multiOpt'] == '0') $rentProductValue['tgrade'] = $productoptions[0]['grade'];
				if($rentProductValue['istrust'] == '0' && _isInt($_POST['trustCommi'])) $rentProductValue['trustCommi'] = $_POST['trustCommi'];				
				$rentProductResult = rentProductSave( $rentProductValue );
				rentProduct::updateOptions($pridx,$productoptions);
				
			}
			
			// 멀티 컨텐츠 처리
			if(!_empty($_REQUEST['chkstamp'])){
				@exec("rename t".abs($_REQUEST['chkstamp'])." ".$code.$productcode." ".$Dir.DataDir."shopimages/multi/t".abs($_REQUEST['chkstamp'])."*");
				@exec("rename thumb_t".abs($_REQUEST['chkstamp'])." thumb_".$code.$productcode." ".$Dir.DataDir."shopimages/multi/thumb_t".abs($_REQUEST['chkstamp'])."*");
				$sql = "update product_multicontents set pridx='".$pridx."',cont=replace(cont,'t".abs($_REQUEST['chkstamp'])."','".$code.$productcode."') where pridx='".$_REQUEST['chkstamp']."'";
				@mysql_query($sql,get_db_conn());
				
			}
			//product_multicontents
			/* 개별 수수료 저장 jdy */
			$up_rq_com = $_REQUEST['up_rq_com'];
			$up_rq_cost = $_REQUEST['up_rq_cost'];
			insertCommission($vender, $code.$productcode, $up_rq_com, $up_rq_cost, "","1", $_usersession->id);
			/* 개별 수수료 저장 jdy */

			// 상품정보고시 저장
			
			$ditems = array();
			if( count($_REQUEST['didx']) ) {
				foreach($_REQUEST['didx'] as $k=>$v){
					$item = array();
					$item['didx'] = $v;
					$item['dtitle'] = $_REQUEST['dtitle'][$k];
					$item['dcontent'] = $_REQUEST['dcontent'][$k];
					array_push($ditems,$item);
				}
			}
			_editProductDetails($pridx,$ditems);



			#추가 카테고리 입력 시작
			$arr_cate=$_POST["cate"];
			for($i=0;$i<sizeof($arr_cate);$i++){
				$sql = "SELECT categorycode FROM tblcategorycode WHERE productcode = '".$code.$productcode."' ";
				$sql.= "AND categorycode	= '".$arr_cate[$i]."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				if(!$row){
					$sql = "INSERT tblcategorycode SET ";
					$sql.= "productcode		= '".$code.$productcode."', ";
					$sql.= "categorycode	= '".$arr_cate[$i]."' ";
					$insert = mysql_query($sql,get_db_conn());
				}
			}
			#추가 카테고리 입력 끝

			if(strlen($brandname)>0) { // 브랜드 관련 처리
				$result = mysql_query("SELECT bridx FROM tblproductbrand WHERE brandname = '".$brandname."' ",get_db_conn());
				if ($row=mysql_fetch_object($result)) {
					@mysql_query("UPDATE tblproduct SET brand = '".$row->bridx."' WHERE productcode = '".$code.$productcode."'",get_db_conn());
				} else {
					$sql = "INSERT tblproductbrand SET brandname = '".$brandname."'";
					if($brandinsert = @mysql_query($sql,get_db_conn())) {
						$bridx = @mysql_insert_id(get_db_conn());
						if($bridx>0) {
							@mysql_query("UPDATE tblproduct SET brand = '".$bridx."' WHERE productcode = '".$code.$productcode."'",get_db_conn());
						}
					}
				}
				mysql_free_result($result);
			}

			/*if($group_check=="Y" && count($group_code)>0) {
				for($i=0; $i<count($group_code); $i++) {
					$sql = "INSERT tblproductgroupcode SET ";
					$sql.= "productcode = '".$code.$productcode."', ";
					$sql.= "group_code = '".$group_code[$i]."' ";
					mysql_query($sql,get_db_conn());
				}
			}*/
			if($group_check=="Y" && count($group_sel_code)>0) {
				for($i=0; $i<count($group_sel_code); $i++) {
					$sql = "INSERT tblproductgroupcode SET ";
					$sql.= "productcode = '".$code.$productcode."', ";
					$sql.= "group_code = '".$group_sel_code[$i]."' ";
					mysql_query($sql,get_db_conn());
				}
			}

			$content=$in_content;
			$use_imgurl="";
			$userfile_url="";
			$userfile2_url="";
			$userfile3_url="";

			
			$sql2 = "insert vender_rent SET ";
			$sql2.= "vender				= '".$vender."', ";
			$sql2.= "pridx				= '".$pridx."', ";
			$sql2.= "rent_stime			= '".$rent_stime."', ";
			$sql2.= "rent_etime			= '".$rent_etime."', ";
			$sql2.= "pricetype			= '".$pricetype."', ";
			$sql2.= "useseason			= '".$useseason."', ";
			$sql2.= "base_period		= '".$base_period."', ";
			$sql2.= "ownership			= '".$ownership."', ";
			$sql2.= "base_time			= '".$base_time."', ";
			$sql2.= "base_price			= '".$base_price."', ";
			$sql2.= "timeover_price		= '".$timeover_price."', ";
			$sql2.= "halfday			= '".$halfday."', ";
			$sql2.= "halfday_percent	= '".$halfday_percent."', ";
			$sql2.= "oneday_ex			= '".$oneday_ex."', ";
			$sql2.= "time_percent		= '".$time_percent."', ";
			$sql2.= "checkin_time		= '".$checkin_time."', ";
			$sql2.= "checkout_time		= '".$checkout_time."', ";
			$sql2.= "cancel_cont		= '".$cancel_cont."', ";
			$sql2.= "discount_card		= '".$discount_card."' ";
			mysql_query($sql2,get_db_conn());

			################################상품별 회원할인율######################################
			for($i=0;$i<count($group_code);$i++) {
				$discountval = 0;
				if($discount[$i] > 0){
					if($discount_type[$i] != '100'){
						$discountval = intval($discount[$i]);
					}else if($discount_type[$i] == '100' && intval($discount[$i]) < 100){
						$discountval = floatval($discount[$i]/100);
					}
				}
				
				$sql = "insert into tblmemberdiscount (group_code,productcode,discountYN,discount,over_discount) values ('".$group_code[$i]."','".$code.$productcode."','".$discountYN."','".$discountval."','".$over_discount."') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),discount = values(discount),over_discount = values(over_discount)";
				mysql_query($sql,get_db_conn());				
			}


			//장기대여
			$dsql = "delete from vender_longrent where vender='0' and pridx='".$pridx."'";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
				for($i=0;$i<count($_POST['longrent_sday']);$i++){
					if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
						$sql2 = "insert into vender_longrent set vender='0',pridx='".$pridx."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
						mysql_query($sql2,get_db_conn());
					}
				}
			}

			//환불
			$dsql = "delete from vender_refund where vender='0' and pridx='".$pridx."'";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){
				for($i=0;$i<count($_POST['refundday']);$i++){
					if($_POST['refundpercent'][$i]>=0){
						$sql_refund = "insert into vender_refund set vender='0',pridx='".$pridx."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
						mysql_query($sql_refund,get_db_conn());
					}
				}
			}
			
			//장기할인
			$dsql = "delete from vender_longdiscount where vender='0' and pridx='".$pridx."'";	
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
				for($i=0;$i<count($_POST['discrangeday']);$i++){
					if($_POST['discrangeday'][$i]>=0 && $_POST['discrangepercent'][$i]>=0){
						$sql_disc = "insert into vender_longdiscount  set vender='0',pridx='".$pridx."',day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
						mysql_query($sql_disc,get_db_conn());
					}
				}
			}
			

			if($popup=="YES") {
				$onload="<script>alert(\"상품이 등록이 완료되었습니다.".$message."\");</script>";
			} else {
				$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"상품이 등록이 완료되었습니다.".$message."\");</script>";
			}

			$log_content = "## 상품입력 ## - 코드 $code$productcode - 상품 : $productname 코디/조립 : $assembleuse 패키지그룹 : $package_num 가격 : $sellprice 수량 : $quantity 기타 : $etctype 적립금: $reserve $display";
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		} else {
			if($popup=="YES") {
				$onload="<script>alert(\"상품 등록중 오류가 발생하였습니다.\");</script>";
			} else {
				$onload="<script>parent.HiddenFrame.alert(\"상품 등록중 오류가 발생하였습니다.\");</script>";
			}
		}
		$prcode=$code.$productcode;
	} else if ($mode=="delete") {
		$sql = "SELECT vender,display,brand,pridx,assembleuse,assembleproduct,wideimage FROM tblproduct WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row->content,$edimg)){
			foreach($edimg[1] as $timg){
				@unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
		}

		if(is_file($savewideimage.$row->wideimage) && $mode=="delete"){
			@unlink($savewideimage.$row->wideimage);
		}
		/** #에디터 관련 파일 처리 추가 부분 */

		$vender=(int)$row->vender;
		$vdisp=$row->display;
		$brand=$row->brand;
		$vpridx=$row->pridx;
		$vassembleuse=$row->assembleuse;
		$vassembleproduct=$row->assembleproduct;


		// 필수 정보 지우기
		$sql = "delete from tblproduct_detail WHERE pridx = '".$vpridx."'";
		mysql_query($sql,get_db_conn());
		
		// 할인 정보 지우기
		$sql = "delete from tblmemberdiscount WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#태그관련 지우기
		$sql = "DELETE FROM tbltagproduct WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#리뷰 지우기
		$sql = "DELETE FROM tblproductreview WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#위시리스트 지우기
		$sql = "DELETE FROM tblwishlist WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#관련상품 지우기
		$sql = "DELETE FROM tblcollection WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproducttheme WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproduct WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproductgroupcode WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#추가 카테고리 관련 지우기
		$sql = "DELETE FROM tblcategorycode WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		/* 추가 수수료 테이블 내용 삭제 jdy */
		$sql = "DELETE FROM product_commission WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());
		/* 추가 수수료 테이블 내용 삭제 jdy */
		
		//환불삭제
		$sql = "DELETE FROM vender_refund WHERE pridx = '".$vpridx."'";
		mysql_query($sql,get_db_conn());
		
		//장기할인삭제
		$sql = "DELETE FROM vender_longdiscount WHERE pridx = '".$vpridx."'";
		mysql_query($sql,get_db_conn());

		//검색키워드 삭제
		$sql = "DELETE FROM tblkeyword WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		if($vassembleuse=="Y") {
			$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
			$sql.= "WHERE productcode = '".$prcode."' ";
			$result = mysql_query($sql,get_db_conn());
			if($row = @mysql_fetch_object($result)) {
				$sql = "DELETE FROM tblassembleproduct WHERE productcode = '".$prcode."' ";
				mysql_query($sql,get_db_conn());

				if(strlen(str_replace("","",$row->assemble_pridx))>0) {
					$sql = "UPDATE tblproduct SET ";
					$sql.= "assembleproduct = REPLACE(assembleproduct,',".$prcode."','') ";
					$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
					$sql.= "AND assembleuse != 'Y' ";
					mysql_query($sql,get_db_conn());
				}
			}
			mysql_free_result($result);
		} else {
			if(strlen($vassembleproduct)>0) {
				$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
				$sql.= "WHERE productcode IN ('".str_replace(",","','",$vassembleproduct)."') ";
				$result = mysql_query($sql,get_db_conn());
				while($row = @mysql_fetch_object($result)) {
					$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
					$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
					$sql.= "AND display ='Y' ";
					$sql.= "AND assembleuse!='Y' ";
					$result2 = mysql_query($sql,get_db_conn());
					if($row2 = @mysql_fetch_object($result2)) {
						$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
						$sql.= "WHERE productcode = '".$row->productcode."' ";
						$sql.= "AND assembleuse='Y' ";
						mysql_query($sql,get_db_conn());
					}
					mysql_free_result($result2);
				}
			}

			$sql = "UPDATE tblassembleproduct SET ";
			$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$vpridx."',''), ";
			$sql.= "assemble_list=REPLACE(assemble_list,',".$vpridx."','') ";
			mysql_query($sql,get_db_conn());
		}

		if($vender>0) {
			//미니샵 테마코드에 등록된 상품 삭제
			setVenderThemeDeleteNor($prcode, $vender);
			setVenderCountUpdateMin($vender, $vdisp);

			$tmpcodeA=substr($prcode,0,3);
			$sql = "SELECT COUNT(*) as cnt FROM tblproduct ";
			$sql.= "WHERE productcode LIKE '".$tmpcodeA."%' AND vender='".$vender."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$prcnt=$row->cnt;
			mysql_free_result($result);

			if($prcnt==0) {
				setVenderDesignDeleteNor($tmpcodeA, $vender);
				$imagename=$Dir.DataDir."shopimages/vender/".$vender."_CODE10_".$tmpcodeA.".gif";
				@unlink($imagename);
			}
		}
		
		$sql = "select * from product_multicontents where pridx='".$vpridx."'";
		if(false !== $chkres = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($chkres)){
				while($info = mysql_fetch_assoc($chkres)){
					if($info['type'] == 'img'){
						@unlink($imagepath.'/'.$info['cont']);
						@unlink($imagepath.'/thumb_'.$info['cont']);
					}
				}
				$sql = "delete from product_multicontents where pridx='".$vpridx."'";
				@mysql_query($sql,get_db_conn());
			}
		}

		

		$log_content = "## 상품삭제 ## - 상품코드 $prcode - 상품명 : ".urldecode($productname)." $display";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

		delProductMultiImg("prdelete","",$prcode);
		deleteNewMultiCont($prcode);
		// 상품정보고지 삭제
		_deleteProductDetails($pridx);

		if ($popup=="YES") {
			$onload="<script>alert(\"상품 삭제가 완료되었습니다.\");window.close();opener.location.reload()</script>";
		} else {
			$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"상품 삭제가 완료되었습니다.\");</script>";
		}
		$prcode="";
	} else if ($mode=="modify") {
		$sql = "SELECT vender,display,brand,pridx,assembleuse,sellprice,assembleproduct FROM tblproduct WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row->content,$edtimg)){
			if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)) $edimg[1] =array();
			foreach($edtimg[1] as $cimg){
				if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
			}
		}
		/** #에디터 관련 파일 처리 추가 부분 */

		$vender=(int)$row->vender;
		$vdisp=$row->display;
		$brand=$row->brand;
		$vassembleuse=$row->assembleuse;
		$vpridx=$row->pridx;
		$vsellprice=$row->sellprice;
		$vassembleproduct=$row->assembleproduct;

		if(strlen($buyprice) < 1 ) $buyprice = 0 ;

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$in_content = str_replace('/data/editor_temp/','/data/editor/',$in_content);
		}
		/** #에디터 관련 파일 처리 추가 부분 */

		$sql = "UPDATE tblproduct SET ";
		$sql.= "productname		= '".$productname."', ";
		$sql.= "prmsg		= '".$prmsg."', ";
		$sql.= "consumerprice	= ".$consumerprice.", ";
		$sql.= "discountRate	= '".$discountRate."', ";
		$sql.= "buyprice		= ".$buyprice.", ";
		$sql.= "reserve			= '".$reserve."', ";
		$sql.= "reservetype		= '".$reservetype."', ";
		$sql.= "production		= '".$production."', ";
		$sql.= "madein			= '".$madein."', ";
		$sql.= "model			= '".$model."', ";
		$sql.= "opendate		= '".$opendate."', ";
		$sql.= "selfcode		= '".$selfcode."', ";
		$sql.= "bisinesscode	= '".$bisinesscode."', ";
		$sql.= "quantity		= ".$quantity.", ";
		$sql.= "group_check		= '".$group_check."', ";
		$sql.= "keyword			= '".$keyword."', ";

		$sql.= "booking_confirm	= '".$booking_confirm."', ";

		//검색 키워드등록 start
		$kw_idx = $_POST["kw_idx"];
		$arrKeyword = "";

		for($i=0;$i<sizeof($kw_idx);$i++){
			$kw = $_POST[$kw_idx[$i]."_kw"];
			if(sizeof($kw)!=0){
				for($j=0;$j<sizeof($kw);$j++){
					$arrKeyword .= $kw[$j]."||";
				}
			}
			
		}
		$arrKeyword = substr($arrKeyword,0,-2);

		
		$sql.= "catekeyword		= '".$arrKeyword."', ";
		//검색키워드등록 end


		$sql.= "addcode			= '".$addcode."', ";
		$sql.= "userspec		= '".$userspec."', ";
		$sql.= "maximage		= '".$image[0]."', ";
		$sql.= "minimage		= '".$image[1]."', ";
		$sql.= "tinyimage		= '".$image[2]."', ";
		if(strlen($attechfilename)>0){
			$sql.= "wideimage		= '".$attechfilename."', ";
		}
		$sql.= "assembleuse		= '".$assembleuse."', ";

		$sql.= "reservation		= '".$reservation."', ";

		// update 시 벤더 처리
		/*
		$vender_id = (!_empty($_POST['vender_name'])?preg_replace('(\([^\)]*\))','',$_POST['vender_name']):'');
		$vender = "0";
		if(!_empty($vender_id)){
			$check = "select vender from tblvenderinfo where id = '".$vender_id."' limit 1";
			$rsd = mysql_query($check,get_db_conn());
			if($rsd && mysql_num_rows($rsd) == 1) $vender = mysql_result($rsd,0,0);
		}
		$sql .= "vender		= '".$vender."', ";
*/
		if($vassembleuse=="Y") {
			if($assembleuse=="Y") {
				$sql.= "assembleproduct	= '', ";
				$sql.= "option_price	= '', ";
				$sql.= "option_quantity	= '', ";
				$sql.= "option1			= '', ";
				$sql.= "option2			= '', ";
				$sql.= "package_num		= '0', ";
			} else {
				$sql.= "assembleproduct	= '', ";
				$sql.= "sellprice		= '".$sellprice."', ";
				$sql.= "option_price	= '".$option_price."', ";
				$sql.= "option_quantity	= '".$optcnt."', ";
				$sql.= "option1			= '".$option1."', ";
				$sql.= "option2			= '".$option2."', ";
				$sql.= "package_num		= '".(int)$package_num."', ";
			}
		} else {
			if($assembleuse=="Y") {
				$sql.= "assembleproduct	= '', ";
				$sql.= "sellprice		= 0, ";
				$sql.= "option_price	= '', ";
				$sql.= "option_quantity	= '', ";
				$sql.= "option1			= '', ";
				$sql.= "option2			= '', ";
				$sql.= "package_num		= '0', ";
			} else {
				$sql.= "sellprice		= '".$sellprice."', ";
				$sql.= "option_price	= '".$option_price."', ";
				$sql.= "option_quantity	= '".$optcnt."', ";
				$sql.= "option1			= '".$option1."', ";
				$sql.= "option2			= '".$option2."', ";
				$sql.= "package_num		= '".(int)$package_num."', ";
			}
		}

		$sql.= "etctype			= '".$etctype."', ";
		// 배송수단 선택 추가
		$sql.= "deli_type		= '".$deli_type."', ";
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		$sql.= "display			= '".$display."', ";

		$sql.= "etcapply_coupon	= '".$etcapply_coupon."', ";
		$sql.= "etcapply_reserve= '".$etcapply_reserve."', ";
		$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
		$sql.= "etcapply_return	= '".$etcapply_return."', ";


		if($insertdate!="Y") {
			$sql.= "date			= '".$curdate."', ";
		}
		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".$in_content."', ";
		$sql.= "sns_state		= '".$sns_state."', ";
		$sql.= "present_state	= '".$present_state."', ";
		$sql.= "pester_state	= '".$pester_state."', ";
		$sql.= "gonggu_product		= '".$gonggu_product."', ";
		$sql.= "sns_reserve1		= '".$sns_reserve1."', ";
		$sql.= "sns_reserve1_type	= '".$sns_reserve1_type."', ";
		$sql.= "sns_reserve2		= '".$sns_reserve2."', ";
		$sql.= "sns_reserve2_type	= '".$sns_reserve2_type."', ";
		$sql.= "first_reserve		= '".$first_reserve."', ";
		$sql.= "first_reserve_type	= '".$first_reserve_type."', ";


		$sql.= "syncNaverEp	= '".(($_POST['syncNaverEp'] =='0')?'0':'1')."', ";

		$sql.= "productdisprice	= '".$productdisprice."', ";

		$sql.= "tax_yn = '".$tax_yn."', ";
		$sql.= "rental = '".$_POST["goodsType"]."', ";
		$sql.= "today_reserve = '".$today_reserve."', "; //당일예약추가
		$sql.= "reseller_reserve = '".$reseller_reserve."', "; //추가
		$sql.= "reseller_reserve_no_use = '".$reseller_reserve_no_use."' "; //추가

		$sql.= "WHERE productcode = '".$prcode."' ";

		if($update = mysql_query($sql,get_db_conn())) {
			
			
			// 렌탈 옵션 처리
			if($_REQUEST['goodsType'] == '2'){
				
				// 대여 상품 저장
				$rentProductValue = array();
				$rentProductValue['pridx'] = $vpridx;
				$rentProductValue['istrust'] = $_POST["istrust"];
				$rentProductValue['location'] = $_POST["location"];
				$rentProductValue['goodsType'] = $_POST["goodsType"];
				$rentProductValue['itemType'] = $_POST["itemType"];			
				$rentProductValue['multiOpt'] = ($_REQUEST['multiOpt'] == '1')?'1':'0';
				if($rentProductValue['multiOpt'] == '0') $rentProductValue['tgrade'] = $productoptions[0]['grade'];
				if($rentProductValue['istrust'] == '0' && _isInt($_POST['trustCommi'])) $rentProductValue['trustCommi'] = $_POST['trustCommi'];
			
				$rentProductResult = rentProductSave( $rentProductValue );
				
				rentProduct::updateOptions($vpridx,$productoptions);
				
			}
			
			

			// 상품정보고지 수정
			$sql = "select pridx from tblproduct WHERE productcode = '".$prcode."'  limit 1";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				$pridx = mysql_result($res,0,0);
				$ditems = array();
				if( count($_REQUEST['didx']) ) {
					foreach($_REQUEST['didx'] as $k=>$v){
						$item = array();
						$item['didx'] = $v;
						$item['dtitle'] = $_REQUEST['dtitle'][$k];
						$item['dcontent'] = $_REQUEST['dcontent'][$k];
						array_push($ditems,$item);
					}
				}
				_editProductDetails($pridx,$ditems);
			}



			#추가 카테고리 입력 시작
			$arr_cate=$_POST["cate"];
			#==삭제==
			$sql = "DELETE FROM tblcategorycode WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#==재입력==
			for($i=0;$i<sizeof($arr_cate);$i++){
				$sql = "SELECT categorycode FROM tblcategorycode WHERE productcode = '".$prcode."' ";
				$sql.= "AND categorycode	= '".$arr_cate[$i]."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				if(!$row){
					$sql = "INSERT tblcategorycode SET ";
					$sql.= "productcode		= '".$prcode."', ";
					$sql.= "categorycode	= '".$arr_cate[$i]."' ";
					$insert = mysql_query($sql,get_db_conn());
				}

			}
			#추가 카테고리 입력 끝

			if(strlen($brandname)>0) { // 브랜드 관련 처리
				$result = mysql_query("SELECT bridx FROM tblproductbrand WHERE brandname = '".$brandname."' ",get_db_conn());
				if ($row=mysql_fetch_object($result)) {
					if($brand != $row->bridx) {
						@mysql_query("UPDATE tblproduct SET brand = '".$row->bridx."' WHERE productcode = '".$prcode."'",get_db_conn());
					}
				} else {
					$sql = "INSERT tblproductbrand SET brandname = '".$brandname."'";
					if($brandinsert = @mysql_query($sql,get_db_conn())) {
						$bridx = @mysql_insert_id(get_db_conn());
						if($bridx>0) {
							@mysql_query("UPDATE tblproduct SET brand = '".$bridx."' WHERE productcode = '".$prcode."'",get_db_conn());
						}
					}
				}
				mysql_free_result($result);
			} else {
				if($brand>0) {
					@mysql_query("UPDATE tblproduct SET brand = null WHERE productcode = '".$prcode."'",get_db_conn());
				}
			}

			$groupdelete = mysql_query("DELETE FROM tblproductgroupcode WHERE productcode = '".$prcode."' ",get_db_conn());
			/*if($groupdelete) {
				if($group_check=="Y" && count($group_code)>0) {
					for($i=0; $i<count($group_code); $i++) {
						$sql = "INSERT tblproductgroupcode SET ";
						$sql.= "productcode = '".$prcode."', ";
						$sql.= "group_code = '".$group_code[$i]."' ";
						@mysql_query($sql,get_db_conn());
					}
				}
			}*/
			if($groupdelete) {
				if($group_check=="Y" && count($group_sel_code)>0) {
					for($i=0; $i<count($group_sel_code); $i++) {
						$sql = "INSERT tblproductgroupcode SET ";
						$sql.= "productcode = '".$prcode."', ";
						$sql.= "group_code = '".$group_sel_code[$i]."' ";
						//echo $sql.'<br>';exit;
						@mysql_query($sql,get_db_conn());
					}
					//exit;
				}
			}


			if($vassembleuse=="Y") {
				if($assembleuse!="Y") {
					$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
					$sql.= "WHERE productcode = '".$prcode."' ";
					$result = mysql_query($sql,get_db_conn());
					if($row = @mysql_fetch_object($result)) {
						$sql = "DELETE FROM tblassembleproduct WHERE productcode = '".$prcode."' ";
						mysql_query($sql,get_db_conn());

						if(strlen(str_replace("","",$row->assemble_pridx))>0) {
							$sql = "UPDATE tblproduct SET ";
							$sql.= "assembleproduct = REPLACE(assembleproduct,',".$prcode."','') ";
							$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
							$sql.= "AND assembleuse != 'Y' ";
							mysql_query($sql,get_db_conn());
						}
					}
					mysql_free_result($result);
				}
			} else {
				if($assembleuse=="Y" || ($assembleuse!="Y" && $vsellprice!=$sellprice)) {
					if(strlen($vassembleproduct)>0) {
						$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
						$sql.= "WHERE productcode IN ('".str_replace(",","','",$vassembleproduct)."') ";
						$result = mysql_query($sql,get_db_conn());
						while($row = @mysql_fetch_object($result)) {
							$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
							$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
							$sql.= "AND display ='Y' ";
							$sql.= "AND assembleuse!='Y' ";
							$result2 = mysql_query($sql,get_db_conn());
							if($row2 = @mysql_fetch_object($result2)) {
								$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
								$sql.= "WHERE productcode = '".$row->productcode."' ";
								$sql.= "AND assembleuse='Y' ";
								mysql_query($sql,get_db_conn());
							}
							mysql_free_result($result2);
						}
					}

					if($assembleuse=="Y") {
						$sql = "UPDATE tblassembleproduct SET ";
						$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$vpridx."',''), ";
						$sql.= "assemble_list=REPLACE(assemble_list,',".$vpridx."','') ";
						mysql_query($sql,get_db_conn());
					}
				}
			}

			if($vender>0) {
				if($vdisp!=$display) {
					setVenderCountUpdateRan($vender, $display);
				}
			}
			$content=$in_content;
			$use_imgurl="";
			$userfile_url="";
			$userfile2_url="";
			$userfile3_url="";

			$sql = "SELECT * FROM vender_rent ";
			$sql.= "WHERE pridx='".$vpridx."'";

			$result=mysql_query($sql,get_db_conn());
			$_ptdata=mysql_fetch_object($result);
			mysql_free_result($result);

			if($_ptdata->vender){
				$sql2 = "UPDATE vender_rent SET ";
				$sql2.= "rent_stime			= '".$rent_stime."', ";
				$sql2.= "rent_etime			= '".$rent_etime."', ";
				$sql2.= "pricetype			= '".$pricetype."', ";
				$sql2.= "useseason			= '".$useseason."', ";
				$sql2.= "base_period		= '".$base_period."', ";
				$sql2.= "ownership			= '".$ownership."', ";
				$sql2.= "base_time			= '".$base_time."', ";
				$sql2.= "base_price			= '".$base_price."', ";
				$sql2.= "timeover_price		= '".$timeover_price."', ";
				$sql2.= "halfday			= '".$halfday."', ";
				$sql2.= "halfday_percent	= '".$halfday_percent."', ";
				$sql2.= "oneday_ex			= '".$oneday_ex."', ";
				$sql2.= "time_percent		= '".$time_percent."', ";
				$sql2.= "checkin_time		= '".$checkin_time."', ";
				$sql2.= "checkout_time		= '".$checkout_time."', ";
				$sql2.= "cancel_cont		= '".$cancel_cont."', ";
				$sql2.= "discount_card		= '".$discount_card."' ";
				$sql2.= "where pridx='".$vpridx."'";
				mysql_query($sql2,get_db_conn());

			}else{
				$sql2 = "insert vender_rent SET ";
				$sql2.= "vender				= '".$vender."', ";
				$sql2.= "pridx				= '".$vpridx."', ";
				$sql2.= "rent_stime			= '".$rent_stime."', ";
				$sql2.= "rent_etime			= '".$rent_etime."', ";
				$sql2.= "pricetype			= '".$pricetype."', ";
				$sql2.= "useseason			= '".$useseason."', ";
				$sql2.= "base_period		= '".$base_period."', ";
				$sql2.= "ownership			= '".$ownership."', ";
				$sql2.= "base_time			= '".$base_time."', ";
				$sql2.= "base_price			= '".$base_price."', ";
				$sql2.= "timeover_price		= '".$timeover_price."', ";
				$sql2.= "halfday			= '".$halfday."', ";
				$sql2.= "halfday_percent	= '".$halfday_percent."', ";
				$sql2.= "oneday_ex			= '".$oneday_ex."', ";
				$sql2.= "time_percent		= '".$time_percent."', ";
				$sql2.= "checkin_time		= '".$checkin_time."', ";
				$sql2.= "checkout_time		= '".$checkout_time."', ";
				$sql2.= "cancel_cont		= '".$cancel_cont."', ";
				$sql2.= "discount_card		= '".$discount_card."' ";
				mysql_query($sql2,get_db_conn());
			}

			if($useseason==0){
				$sql_ = "delete from vender_season_range where vender=".$vender." and pridx='".$vpridx."'";
				mysql_query($sql_,get_db_conn());

				$sql_2 = "delete from vender_holiday_list where vender=".$vender." and pridx='".$vpridx."'";
				mysql_query($sql_2,get_db_conn());
			}


		}

		################################상품별 회원할인율######################################
		for($i=0;$i<count($group_code);$i++) {
			$discountval = 0;
			if($discount[$i] > 0){
				if($discount_type[$i] != '100'){
					$discountval = intval($discount[$i]);
				}else if($discount_type[$i] == '100' && intval($discount[$i]) < 100){
					$discountval = floatval($discount[$i]/100);
				}
			}
			$sql = "insert into tblmemberdiscount (group_code,productcode,discountYN,discount,over_discount) values ('".$group_code[$i]."','".$prcode."','".$discountYN."','".$discountval."','".$over_discount."') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),discount = values(discount),over_discount = values(over_discount)";
			mysql_query($sql,get_db_conn());				
		}
		
		//장기대여
		$dsql = "delete from vender_longrent where vender='0' and pridx='".$vpridx."'";
		mysql_query($dsql,get_db_conn());
		if(_array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
			for($i=0;$i<count($_POST['longrent_sday']);$i++){
				if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
					$sql2 = "insert into vender_longrent set vender='0',pridx='".$vpridx."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
					mysql_query($sql2,get_db_conn());
				}
			}
		}

		//환불
		$dsql = "delete from vender_refund where vender='0' and pridx='".$vpridx."'";
		mysql_query($dsql,get_db_conn());
		if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){
			for($i=0;$i<count($_POST['refundday']);$i++){
				if($_POST['refundpercent'][$i]>=0){
					$sql_refund = "insert into vender_refund set vender='0',pridx='".$vpridx."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
					mysql_query($sql_refund,get_db_conn());
				}
			}
		}
		
		//장기할인
		$dsql = "delete from vender_longdiscount where vender='0' and pridx='".$vpridx."'";	
		mysql_query($dsql,get_db_conn());
		if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
			for($i=0;$i<count($_POST['discrangeday']);$i++){
				if($_POST['discrangeday'][$i]>=0 && $_POST['discrangepercent'][$i]>=0){
					$sql_disc = "insert into vender_longdiscount  set vender='0',pridx='".$vpridx."',day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
					mysql_query($sql_disc,get_db_conn());
				}
			}
		}
		
		if($popup=="YES") {
			$onload="<script>alert(\"상품이 수정되었습니다.$message\");</script>";
		} else {
			$onload="<script>parent.ListFrame.GoPageReload();parent.HiddenFrame.alert(\"상품이 수정되었습니다.$message\");</script>";
		}

		$log_content = "## 상품수정 ## - 코드 $prcode - 상품 : $productname 가격 : $sellprice 수량 : $quantity 기타 : $etctype 적립금 : $reserve 날짜고정 : ".(($insertdate=="Y")?"Y":"N")." $display";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}
} else {
	$onload="<script>alert(\"상품이미지의 총 용량이 ".ceil($file_size/1024)
	."Kbyte로 500K가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 500K입니다.\\n\\n"
	."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.\");history.go(-1);</script>\n";
}

//################# 500K가 넘는 이미지 체크
if ((strlen($userfile[name])>0 && $userfile[size]==0) || (strlen($userfile2[name])>0 && $userfile2[size]==0) || (strlen($userfile3[name])>0 && $userfile3[size]==0)) {
	 $onload="<script>alert(\"상품이미지중 용량이 500K가 넘는 이미지가 있습니다.\\n\\n500K가 넘는 이미지는 등록되지 않습니다..\\n\\n"
		."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.\");</script>\n";
}
//###############################################

//수수료 적용 jdy
if ($mode =="comm_ok") {

	$commission_result = $_POST['commission_result'];

	if ($prcode) {
		confirmCommission($prcode, $commission_result, $_usersession->id);
	}

	if($popup=="YES") {
		$onload="<script>alert(\"수수료 변경이 완료 되었습니다.\");</script>";
	} else {
		$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"수수료 변경이 완료 되었습니다.\");</script>";
	}

}


if ($mode =="comm_admin") {

	$sql = "select vender from tblproduct where productcode='".$prcode."'";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_array($result);


	if ($prcode) {
		/* 개별 수수료 저장 jdy */
		$up_rq_com = $_REQUEST['up_rq_com'];
		$up_rq_cost = $_REQUEST['up_rq_cost'];
		insertCommission($data[0], $prcode, $up_rq_com, $up_rq_cost, "", "1", $_usersession->id);
		/* 개별 수수료 저장 jdy */
	}
	if($popup=="YES") {
		$onload="<script>alert(\"수수료 변경이 완료 되었습니다.\");</script>";
	} else {
		$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"수수료 변경이 완료 되었습니다.\");</script>";
	}

}
//수수료 적용 jdy
?>
<? include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript"> var $j = jQuery.noConflict();</script>
<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--
<script language="javascript" type="text/javascript" src="/js/fxTextAreaAutoResizer.js"></script> -->

<script type="text/javascript" src="<?=$Dir?>lib/DropDown.js.php"></script>
<script>var LH = new LH_create();</script>
<script for="window" event="onload">LH.exec();</script>
<script>LH.add("parent_resizeIframe('AddFrame')");</script>
<style type="text/css">
@import url("/css/common.css");
#showMemSale {
	width: 240px;
	margin: 0px;
	padding: 10px;
	position: absolute;
	background: #ffffff;
	color: #666;
	font-size: 11px;
	font-family: 돋움;
	font-weight: 100;
	border: 1 solid #ccc;
visible;
	z-index: 100;
	visibility: hidden;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}

function PrdtDelete() {
	if (confirm("해당 상품을 삭제하시겠습니까?")) {
		document.cForm.mode.value="delete";
		document.cForm.submit();
	}
}

function NewPrdtInsert(){
	document.cForm.prcode.value="";
	document.cForm.submit();
}

function IconMy(){
	window.open("","icon","height=343,width=440,toolbar=no,menubar=no,scrollbars=no,status=no");
	document.icon.submit();
}

function IconList(){
	alert("서비스 준비중 입니다.");
	//window.open("","iconlist","height=343,width=440,toolbar=no,menubar=no,scrollbars=no,status=no");
	//document.iconlist.submit();
}

function DeletePrdtImg(temp){
	if(confirm('해당 이미지를 삭제하시겠습니까?')){
		document.cForm.mode.value="delprdtimg";
		document.cForm.delprdtimg.value=temp-1;
		document.cForm.submit();
	}
}

function CheckChoiceIcon(no){
	num = document.form1.iconnum.value;
	iconnum=0;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) iconnum++;
	}
	if(iconnum>3){
		alert('한 상품에 3개까지 아이콘을 사용할 수 있습니다.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked==true) alert('상품 중간/작은 이미지가 대 이미지에서 자동 생성됩니다.\n\n기존의 중간/작은 이미지는 삭제됩니다.');
}

var shop="layer0";
var ArrLayer = new Array ("layer0","layer1","layer2","layer3");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementById(ArrLayer[i]).style.display="";
			else
				document.getElementById(ArrLayer[i]).style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
	parent_resizeIframe('AddFrame');
}

function ViewSnsLayer(display) {
	if(document.getElementById("sns_optionWrap"))
		document.getElementById("sns_optionWrap").style.display = display;
}

function SelectColor(){
	setcolor = document.form1.setcolor.value;
	var newcolor = showModalDialog("select_color.php?color="+setcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		document.form1.setcolor.value=newcolor;
		document.all.ColorPreview.style.backgroundColor = '#' + newcolor;
	}
}

function optionhelp(){
	alert("서비스 준비중 입니다.");
}

function DateFixAll(obj) {
	if (obj.checked==true) {
		document.form1.insertdate.value="Y";
		document.form1.insertdate1.checked=true;
		document.form1.insertdate2.checked=true;
		document.form1.insertdate3.checked=true;
	} else {
		document.form1.insertdate.value="";
		document.form1.insertdate1.checked=false;
		document.form1.insertdate2.checked=false;
		document.form1.insertdate3.checked=false;
	}
}

function change_filetype(obj) {
	if(obj.checked==true) {	//이미지 링크 방식
		for(var jj=1;jj<=3;jj++) {
			idx=jj;
			if(idx==1) idx="";
			document.form1["userfile"+idx].style.display='none';
			document.form1["userfile"+idx+"_url"].style.display='';
			document.form1["userfile"+idx].disabled=true;
			document.form1["userfile"+idx+"_url"].disabled=false;
		}
	} else {				//첨부파일 방식
		for(var jj=1;jj<=3;jj++) {
			idx=jj;
			if(idx==1) idx="";
			document.form1["userfile"+idx].style.display='';
			document.form1["userfile"+idx+"_url"].style.display='none';
			document.form1["userfile"+idx].disabled=false;
			document.form1["userfile"+idx+"_url"].disabled=true;
		}
	}
}

function userspec_change(val) {
	if(document.getElementById("userspecidx")) {
		if(val == "Y") {
			document.getElementById("userspecidx").style.display ="";
		} else {
			document.getElementById("userspecidx").style.display ="none";
		}
	}
}

function GroupCode_Change(val) {
	if(document.getElementById("group_checkidx")) {
		if(val == "Y") {
			document.getElementById("group_checkidx").style.display ="";
		} else {
			document.getElementById("group_checkidx").style.display ="none";
		}
	}
}

function GroupCodeAll(checkval,checkcount) {
	for(var i=0; i<checkcount; i++) {
		if(document.getElementById("group_code_idx"+i)) {
			document.getElementById("group_code_idx"+i).checked = checkval;
		}
	}
}

/*################################### 태그관련 시작 #####################################*/
var IE = false ;
if (window.navigator.appName.indexOf("Explorer") !=-1) {
	IE = true;
}

function getXmlHttpRequest() {
	var xmlhttp = false
	if(window.XMLHttpRequest){//Mozila
		xmlhttp = new XMLHttpRequest()
	}else {//IE
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
	}
	return xmlhttp;
}
function loadData(path, successFunc, msg){
	var xmlhttp = getXmlHttpRequest();
	xmlhttp.open("GET",path,true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				var data = xmlhttp.responseText;
				successFunc(data);
			}else{
				alert(msg);
			}
		}
	}
	xmlhttp.send(null);
	return false;
}


function loadProductTagList (prcode) {
	loadData("product_taglist.xml.php?prcode="+prcode, setProductTagList);
}
function setProductTagList(data) {
  	try {
  		var tagElem = document.getElementById("ProductTagList");
  		if(data=='') {
  			data = "일시적으로 태그 정보를 불러올 수 없습니다.\n\n태그 관리 기능은 잠시 후에 이용해 주십시오. \n\n상품수정은  정상적으로 수정하실  수 있습니다.";
  		}
  		tagElem.innerHTML = data;
		tagElem.style.height = "68";
		tagElem.style.overflowY = "auto";
  	}catch(e) {}
}

function delTagName(prcode,tagname) {
<? if(_DEMOSHOP=="OK" && getenv("REMOTE_ADDR")!=_ALLOWIP) { ?>
	alert("데모버전에서는 삭제가 불가능 합니다.");
<? } else { ?>
	if(confirm("\""+tagname+"\" 태그를 삭제하시겠습니까?")) {
		loadData("product_taglist.xml.php?type=del&prcode="+prcode+"&tagname="+tagname, setProductTagList);
	}
<? } ?>
}

function BrandSelect() {
	window.open("product_brandselect.php","brandselect","height=400,width=420,scrollbars=no,resizable=no");
}

function FiledSelect(pagetype) {
	window.open("product_select.php?type="+pagetype,pagetype,"height=400,width=420,scrollbars=no,resizable=no");
}

/*################################### 태그관련 끝   #####################################*/


function deli_helpshow() {
	if(document.getElementById('deli_helpshow_idx')) {
		if(document.getElementById('deli_helpshow_idx').style.display=="none") {
			document.getElementById('deli_helpshow_idx').style.display="";
		} else {
			document.getElementById('deli_helpshow_idx').style.display="none";
		}
	}
}

function chkFieldMaxLenFunc(thisForm,reserveType) {
	if (reserveType=="Y") { max=5; addtext="/특수문자(소수점)";} else { max=6; }
	if (thisForm.reserve.value.bytes() > max) {
		alert("입력할 수 있는 허용 범위가 초과되었습니다.\n\n" + "숫자"+addtext+" " + max + "자 이내로 입력이 가능합니다.");
		thisForm.reserve.value = thisForm.reserve.value.cut(max);
		thisForm.reserve.focus();
	}
}

function getSplitCount(objValue,splitStr)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	return split_array.length;
}

function getPointCount(objValue,splitStr,falsecount)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);

	if(split_array.length!=2) {
		if(split_array.length==1) {
			return false;
		} else {
			return true;
		}
	} else {
		if(split_array[1].length>falsecount) {
			return true;
		} else {
			return false;
		}
	}
}

function isDigitSpecial(objValue,specialStr)
{
	if(specialStr.length>0) {
		var specialStr_code = parseInt(specialStr.charCodeAt(i));

		for(var i=0; i<objValue.length; i++) {
			var code = parseInt(objValue.charCodeAt(i));
			var ch = objValue.substr(i,1).toUpperCase();

			if((ch<"0" || ch>"9") && code!=specialStr_code) {
				return true;
				break;
			}
		}
	} else {
		for(var i=0; i<objValue.length; i++) {
			var ch = objValue.substr(i,1).toUpperCase();
			if(ch<"0" || ch>"9") {
				return true;
				break;
			}
		}
	}
}

<? if($_data->vender<=0) { ?>
function assembleuse_change() {
	if(document.form1.assembleuse[0].checked) {
		document.form1.sellprice.disabled=false;
		document.form1.sellprice.style.backgroundColor = "#FFFFFF";
		if(document.form1.searchtype) {
			if(document.form1.searchtype.length && document.form1.searchtype.length>0) {
				for(var i=0; i<document.form1.searchtype.length; i++) {
					document.form1.searchtype[i].disabled=false;
					if(document.form1.searchtype[i].checked) {
						ViewLayer("layer"+i);
					}
				}
			} else {
				document.form1.searchtype.disabled=false;
				if(document.form1.searchtype.checked) {
					ViewLayer("layer");
				}
			}
			if(document.getElementById("assemblealertidx")) {
				document.getElementById("assemblealertidx").style.display="none";
			}
		}

		if(document.getElementById("packagealertidx")) {
			document.getElementById("packagealertidx").style.display="none";
		}
		if(document.getElementById("packageselectidx")) {
			document.getElementById("packageselectidx").style.display="";
		}
		document.form1.package_num.disabled=false;
	} else {
		document.form1.sellprice.disabled=true;
		document.form1.sellprice.style.backgroundColor = "#C0C0C0";
		if(document.form1.searchtype) {
			ViewLayer("layer0");
			if(document.form1.searchtype.length && document.form1.searchtype.length>0) {
				for(var i=0; i<document.form1.searchtype.length; i++) {
					document.form1.searchtype[i].disabled=true;
				}
			} else {
				document.form1.searchtype.disabled=true;
			}
			if(document.getElementById("assemblealertidx")) {
				document.getElementById("assemblealertidx").style.display="";
			}
		}
		if(document.getElementById("packagealertidx")) {
			document.getElementById("packagealertidx").style.display="";
		}
		if(document.getElementById("packageselectidx")) {
			document.getElementById("packageselectidx").style.display="none";
		}
		document.form1.package_num.disabled=true;
	}
	parent_resizeIframe('AddFrame');
	alert("한번 등록된 상품 판매가격 타입은 변경이 불가능하므로 신중히 선택해 주세요.");
}
<? } ?>

/*#################추가 카테고리 시작#########################*/
function cateDel(el)
{
	idx = el.rowIndex;
	var obj = document.getElementById('objProductCode');
	obj.deleteRow(idx);
}

function cateAdd()
{
	var ret;
	var strText;
	var str = new Array();
	var objA = document.forms[0]['codeA'];
	var objB = document.forms[0]['codeB'];
	var objC = document.forms[0]['codeC'];
	var objD = document.forms[0]['codeD'];

	if (objA.value){
		strText = objA[objA.selectedIndex].text;
		ret = objA.value;
	}
	if (objB.value){
		if (objB.value!='000'){
			strText += " > "+objB[objB.selectedIndex].text;
		}
		ret += objB.value;
	}else{
		ret += "000";
	}
	if (objC.value){
		if (objC.value!='000'){
			strText += " > "+objC[objC.selectedIndex].text;
		}
		ret += objC.value;
	}else{
		ret += "000";
	}
	if (objD.value){
		if (objD.value!='000'){
			strText += " > "+objD[objD.selectedIndex].text;
		}
		ret += objD.value;
	}else{
		ret += "000";
	}

	str[str.length] = strText;

	if (!ret){
		alert('카테고리를 선택해주세요');
		return;
	}else if(ret=="<?=substr($code,0,12)?>"){
		alert('현재 카테고리는 선택할 수 없습니다.');
		return;
	}

	var obj = document.getElementById('objProductCode');
	oTr = obj.insertRow();
	oTd = oTr.insertCell();
	oTd.id = "";
	oTd.innerHTML = str;
	oTd = oTr.insertCell();
	oTd.innerHTML = "\
	<input type='hidden' name='cate[]' value='" + ret + "'>\
	";
	oTd = oTr.insertCell();
	oTd.innerHTML = "<a href='javascript:void(0)' onClick='cateDel(this.parentNode.parentNode)'><input type='button' value='삭제' align=absmiddle></a>";
}
/*#################추가 카테고리 끝#########################*/

function webftp_popup() {
	window.open("design_webftp.popup.php","webftppopup","height=10,width=10");
}


// 판매가 자동계산
function sellpriceAutoCalc ( v ) {

	var sell = document.form1.sellprice;
	var org = document.form1.consumerprice;
	var disc = document.form1.discountRate;

	var sellv = sell.value;
	var orgv = org.value;
	var discv = disc.value;

	if(sellv > 0 && orgv > 0 && discv > 0) {
		if(v=='org'){
			disc.value = parseInt((100*orgv-100*sellv)/orgv);
		}
		if(v=='sell'){
			org.value = parseInt(sellv/((100-discv)/100));
		}
		if(v=='disc'){
			sell.value = parseInt(orgv-orgv*(discv/100));
		}
	} else {
		if(v=='org' && orgv > 0 && sellv > 0){
			disc.value = parseInt((100*orgv-100*sellv)/orgv);
		}
		if(v=='sell' && sellv > 0 && orgv > 0){
			disc.value = parseInt((100*orgv-100*sellv)/orgv);
		}
		if(v=='disc' && orgv > 0 && discv > 0 && sellv == 0){
			sell.value = parseInt(orgv-orgv*(discv/100));
		}
		if(v=='disc' && sellv > 0 && discv > 0 && orgv == 0){
			org.value = parseInt(sellv/((100-discv)/100));
		}
	}

}

/*검색키워드*/
function addKwGroup(){
	$("#kwgroup").val("");
	$(".div_kw").hide();
	$(".div_kw2").show();
}

function addKwCancel(){
	$(".div_kw").show();
	$(".div_kw2").hide();
}

function addKwSend(val){
	var data = "";
	data = 'mode=kwgroup_insert&kwgroup='+$("#kwgroup").val();

	jQuery.ajax({
		url: "./keyword_ajax_process.php",
		type: "POST",
		data: data,
		success: function(res) {
			$("#kw_group").append(res);
			$(".div_kw").show();
			$(".div_kw2").hide();
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function addKwText(idx){
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";
	$(addDiv).hide();
	$(addDiv2).show();
}

function cancelKwText(idx){
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";
	$(addDiv).show();
	$(addDiv2).hide();
}

function addKwSelect(idx,val){
	
	for(i=0;i<document.getElementsByName("kg_idx[]").length;i++){
		if(document.getElementsByName("kg_idx[]")[i].value==idx){
			alert("이미 등록된 분류입니다.");return;
		}
	}

	var data = "";
	data = 'mode=tbl_kw_list&code=<?=$code?>&prcode=<?=$prcode?>&catekeyword=<?=$catekeyword?>&kg_idx='+idx;
	jQuery.ajax({
		url: "./keyword_ajax_process.php",
		type: "POST",
		data: data,
		success: function(res) {
			$(".kw_view ul").append(res);
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function delKwGroup(el){
	$("#div_"+el).remove();
}

function insertKwText(idx){
	var valInput = "#"+idx+"_kw_text";
	var kwgroup = "#"+idx+"_kwgroup";
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";
	
	var data = "";
	data = 'mode=tbl_kw_insert&code=<?=$code?>&prcode=<?=$prcode?>&kg_idx='+idx+'&keyword='+$(valInput).val()+'&kwgroup='+$(kwgroup).val();
	var htmlView = "";

	jQuery.ajax({
		url: "../admin/keyword_ajax_process.php",
		type: "POST",
		data: data,
		success: function(res) {
			$("#"+idx+"_kwlist").append(res);
			$(valInput).val("");
			$(addDiv).show();
			$(addDiv2).hide();
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function delKwText(el){
	$ptr=$(el).parent();
	$ptr.remove();
}

function kwcheckAll(el){
	if($("#ckall_"+el).is(":checked")){
		$(".ck_"+el).prop("checked",true);

		$(".ck_"+el+":checked").each(function (i) {
			if(this.checked){ 
				var cateval = $(this).val().split(":");
				addcatekw(cateval[0],cateval[1],cateval[2]);
			} 
		});

	}else{
		$(".ck_"+el).prop("checked",false);

		$(".ck_"+el+":not(:checked)").each(function (i) {
			var cateval = $(this).val().split(":");
			delcatekw2(cateval[0]);
		});
	}
}

function addcatekw(idx,group,kwtext){
	var inputname = idx+"_kw";
	if($("input[name^="+inputname+"]").is(":checked")){
		var htmlView = "";
		htmlView += "<span id=\"div_"+idx+"\">";
		htmlView += "<input type=\"hidden\" name=\"kw_idx[]\" value=\""+idx+"\">";
		htmlView += group+":"+kwtext;
		htmlView += "<button type=\"button\" onclick=\"delcatekw(this)\" style=\"margin:2px;\">x</button> &nbsp;";
		htmlView += "</span>";
		$(".div_keywordlist").append(htmlView);
	}else{
		delcatekw2(idx);
	}
}

function delcatekw(el){
	$ptr=$(el).parent();
	var kw_idx = $ptr.find("input[name^=kw_idx]").val();
	var inputname = kw_idx+"_kw";
	$("input[name^="+inputname+"]").prop("checked",false);
	$ptr.remove();
}

function delcatekw2(idx){
	$("input[name^="+idx+"_kw]").prop("checked",false);
	$("#div_"+idx).remove();
}
/*검색키워드*/
//-->
</SCRIPT>
<!-- 에디터용 파일 호출 -->
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.autocomplete.css" />
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
	/*
	if($("#vender_name")){
		$("#vender_name").autocomplete("get_course_list.php", {
			width: 260,
			matchContains: true,
			//mustMatch: true,
			//minChars: 0,
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
	}*/
});
</script>
<style type="text/css">
@import url("/gmeditor/common.css");
</style>
<!-- # 에디터용 파일 호출 -->
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
<?	$chkstamp = -1*time(); ?>
	<input type=hidden name="chkstamp" value="<?=$chkstamp?>" />
	<input type=hidden name=mode>
	<input type=hidden name=code value="<?=$code?>">
	<input type=hidden name=prcode value="<?=$prcode?>">
	<input type=hidden name=htmlmode value='wysiwyg'>
	<input type=hidden name=delprdtimg>
	<input type=hidden name=option1>
	<input type=hidden name=option2>
	<input type=hidden name=option_price>
	<input type=hidden name=insertdate>
	<input type=hidden name=popup value="<?=$popup?>">
	<?
if ($code != substr($prcode,0,12)) $prcode = "";
if(!preg_match('/^[0-9]{12}$/',$code)){
	exit('카테고리가 지정 되지 않았습니다.');
}
if (strlen($prcode)>0) {
	/****** 수수료 관련 수정 jdy ************/
	$sql = "SELECT p.*, c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval, p.reservation FROM tblproduct p left join product_commission c on p.productcode=c.productcode WHERE p.productcode = '".$prcode."' ";
	/****** 수수료 관련 수정 jdy ************/

	$result = mysql_query($sql,get_db_conn());

	if ($_data = mysql_fetch_object($result)) {
		$productname = $_data->productname;
		$syncNaverEp = $_data->syncNaverEp;

		if(strlen($_data->option_quantity)>0) $searchtype=1;
		else if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_data->option1)) $searchtype=3;

		unset($specname,$specvalue,$specarray);
		if(strlen($_data->userspec)>0) {
			$userspec = "Y";
			$specarray= explode("=",$_data->userspec);
			for($i=0; $i<$userspec_cnt; $i++) {
				$specarray_exp = explode("", $specarray[$i]);
				$specname[] = $specarray_exp[0];
				$specvalue[] = $specarray_exp[1];
			}
		} else {
			$userspec = "N";
		}

		// 특수옵션값을 체크한다.
		$dicker = $dicker_text="";
		if (strlen($_data->etctype)>0) {
			$etctemp = explode("",$_data->etctype);
			$miniq = 1;		  // 최소주문수량 기본값 넣는다.
			$maxq = "";
			for ($i=0;$i<count($etctemp);$i++) {
				if ($etctemp[$i]=="BANKONLY")					$bankonly="Y";		// 현금전용
				else if (substr($etctemp[$i],0,11)=="DELIINFONO=")	 $deliinfono=substr($etctemp[$i],11);  // 배송/교환/환불정보 노출안함 정보
				else if ($etctemp[$i]=="SETQUOTA")			   $setquota="Y";		// 무이자상품
				else if (substr($etctemp[$i],0,6)=="MINIQ=")	 $miniq=substr($etctemp[$i],6);  // 최소주문수량
				else if (substr($etctemp[$i],0,5)=="MAXQ=")	  $maxq=substr($etctemp[$i],5);  // 최대주문수량
				else if (substr($etctemp[$i],0,5)=="ICON=")	  $iconvalue=substr($etctemp[$i],5);  // 최대주문수량
				else if (substr($etctemp[$i],0,9)=="FREEDELI=")  $freedeli=substr($etctemp[$i],9);  // 무료배송상품
				else if (substr($etctemp[$i],0,7)=="DICKER=") {  $dicker=Y; $dicker_text=str_replace("DICKER=","",$etctemp[$i]); }  // 가격대체문구
			}
		}
		if(strlen($iconvalue)>0) for($i=0;$i<strlen($iconvalue);$i=$i+2) $iconvalue2[substr($iconvalue,$i,2)]="Y";
		
		if($_data->brand>0) {
			$sql = "SELECT brandname FROM tblproductbrand WHERE bridx = '".$_data->brand."' ";
			$result = mysql_query($sql,get_db_conn());
			$_data2 = mysql_fetch_object($result);
			$_data->brandname = $_data2->brandname;
			mysql_free_result($result);
		}

		if($_data->group_check=="Y") {
			$sql = "SELECT group_code FROM tblproductgroupcode WHERE productcode = '".$prcode."' ";
			$result = mysql_query($sql,get_db_conn());
			while($row = mysql_fetch_object($result)) {
				$group_code[$row->group_code] = "Y";
			}
			mysql_free_result($result);
		}
	} else {
		echo "<script>alert('해당 상품이 존재하지 않습니다.');location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}
}

if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_data->option1)){
	$optcode = substr($_data->option1,5,4);
	$_data->option1="";
	$_data->option_price="";
}
?>
	
	<div style="text-align:right">
	<? if (strlen($prcode)==0) { ?>
		<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align=absmiddle width="144" height="38" border="0" vspace="5"></a>
	<? } else {?>
		<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align=absmiddle width="162" height="38" border="0" vspace="5"></B></a> &nbsp; <a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align=absmiddle width="113" height="38" border="0" vspace="5"></B></a>
	<? }?>
	</div>
	
	<div style="width:97%;BORDER:#0F8FCB 2px solid; float:right">
		<? if($popup== "YES") {?>
		<div id="popTitleArea" style="margin:10px 5px 20px 5px;">
			<div style="height:28px; margin-top:17px; text-align:right; background:url(images/top_link_line.gif) left bottom repeat-x;"  class="link"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>현재위치 : 상품관리 &gt;카테고리/상품관리 &gt; <span class="2depth_select">상품 등록 및 수정</span></div>
			<div style=""><IMG SRC="images/product_register_title.gif"  ALT=""><a href="javascript:location.reload()">[refresh]</a></div>
			<div style=" background:url(images/title_bg.gif) repeat-x; height:21px;"></div>
			<div style="padding-left:10px;">판매할 상품을 등록,수정,삭제 합니다. 상품별 기본정보, 가격정보, 추가옵션정보, 상품설명, 상품이미지 등을 지정할 수 있습니다.</div>
		</div>
		<? } ?>
		<style type="text/css">
.reqFld{ background:url(images/icon_point2.gif) 10px 50% no-repeat; color:#FF4C00; padding-left: 17px; font-weight:bold}
.tbltopline{ border-top:1px solid #e3e3e3}
.inputTbl{ border-top:1px solid #e3e3e3; width:100%}
.inputTbl caption{ text-align:left; padding-left:10px; padding-bottom:5px;}
.inputTbl tbody th{  padding:5px; letter-spacing:-0.5pt; line-height:18px; text-align:left; font-size:12px; color:4b4b4b; border-bottom:1px solid #e3e3e3; width:120px; background:#f8f8f8 url(images/icon_point5.gif) 10px 50% no-repeat; padding-left:17px;}.inputTbl tbody td{ padding:5px; color:#949494; font-size:12px; line-height:19px; border-left:1px solid #e3e3e3; border-bottom:1px solid #e3e3e3;}
.inputTbl tbody th.reqFld{ background:#f8f8f8 url(images/icon_point2.gif) 10px 50% no-repeat; color:#FF4C00; padding-left: 17px; font-weight:bold}
</style>	
		
	
		<table border="0" cellpadding="0" cellspacing="0" class="inputTbl">
			<caption>
			<IMG SRC="images/product_register_stitle1.gif" ALT="">
			<div class="notice_blue reqFld" style="margin-left:10px;">필수표시 항목</div>
			</caption>
			<tbody>
				<tr>
					<th>상품 구분</Th>
					<td colspan="3">
						<?
			$categoryRentInfo = categoryRentInfo($code);					
			$rentProduct = rentProduct($_data->pridx);
			//_pr($rentProduct);
			if(_array($rentProduct) ){
				$itemTypeSel[$rentProduct['itemType']] = "checked";						
			} else {
				$itemTypeSel['product'] = "checked";
			}
			
			if($_data->rental == '2') $goodsTypeSel[$_data->rental] = "checked";
			else $goodsTypeSel[1] = "checked";
			?>
						<script language="javascript" type="text/javascript">
			function toggleGoodsType(val){
				if(val == '2'){ // 렌탈 상품
					$j('.rentalItemArea').css('display','');
					$j('.productItemArea').css('display','none');

					$j('#rentOptTable').find('.optMulti').css('display','none');
					$j('#rentOptTable').find('caption').css('display','none');
				}else{
					$j('.rentalItemArea').css('display','none');
					$j('.productItemArea').css('display','');
				}
				
				parent_resizeIframe('AddFrame');
			}
			
			$j(function(){
				toggleGoodsType('<?=$_data->rental?>');
			});
			</script>
						<? if(_isInt($_data->pridx)){ ?>
						<input type="hidden" name="goodsType" value="<?=$_data->rental?>" />
						<? echo ($_data->rental=='2')?'대여상품':'판매상품'; ?><a href="javascript:document.location.reload()">[Refresh]</a>
						<?	}else{ ?>
						<input type=radio id="goodsType1" name="goodsType" value="1" <?=$goodsTypeSel['1']?> onclick="toggleGoodsType('1');">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType1>판매상품</label>
						&nbsp;
						<? if(_array($categoryRentInfo)){ ?>
						<input type=radio id="goodsType2" name="goodsType" value="2" <?=$goodsTypeSel['2']?> onclick="toggleGoodsType('2'); ">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType2>대여상품</label>
						<a href="javascript:document.location.reload()">
						<? } ?>
						</a>
						<?	} ?>
					</td>
				</tr>
				<? if($_data->vender>0){?>
				<tr>
					<th>등록업체</th>
					<td colspan="3">
						<?
			$sql = "SELECT vender,id,brand_name FROM tblvenderstore WHERE vender='".$_data->vender."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				echo "<A HREF=\"javascript:viewVenderInfo(".$row->vender.")\"><B>".$row->brand_name." (".$row->id.")</B></A>";
			}
			mysql_free_result($result);
			//벤더 정보 변수에 담아두는 부분
			$vender = $row->vender;
			$vender_name = $row->brand_name;
			$vender_id = $row->id;
			?>
					</td>
				</tr>
				<? }?>
				<tr>
					<th>등록/수정일</th>
					<td colspan="3">
						<?
				if (strlen($prcode)==0) echo "자동입력";
				else {
					if ($_data) {
						echo " ".str_replace("-","/",substr($_data->modifydate,0,16))."\n";
						echo "(상품코드 : <span class=\"font_orange\">".$_data->productcode."</span>)";
						echo "&nbsp;&nbsp;&nbsp;<a href=\"http://".$shopurl."?productcode=".$_data->productcode."\" target=_blank><img src=\"images/productregister_goproduct.gif\" align=absmiddle border=0></font></a>";
					}
					echo "<input type=hidden name=productcode value=\"".$_data->productcode."\">\n";
				}
			?>
					</td>
				</tr>
				<tr>
					<th>메인 카테고리</th>
					<td colspan="3" style="word-break:break-all;">
						<?
			$code_loc = "";
			$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
			if(substr($code,3,3)!="000") {
				$sql.= "AND (codeB='".substr($code,3,3)."' OR codeB='000') ";
				if(substr($code,6,3)!="000") {
					$sql.= "AND (codeC='".substr($code,6,3)."' OR codeC='000') ";
					if(substr($code,9,3)!="000") $sql.= "AND (codeD='".substr($code,9,3)."' OR codeD='000') ";
					else $sql.= "AND codeD='000' ";
				}else{
					$sql.= "AND codeC='000' ";
				}
			}else{
				$sql.= "AND codeB='000' AND codeC='000' ";
			}
			$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				if($i>0) $code_loc.= " > ";
				$code_loc.= $row->code_name;
				$i++;
			}
			mysql_free_result($result);

			if (strlen($prcode)>0) echo $code_loc." > <B><span class=\"font_orange\">".$productname."</B></span>";
			else echo $code_loc." > <B><span class=\"font_orange\">".($gongtype=="Y"?"공동구매 신규입력":"신규입력")."</B></span>";
?>
					</td>
				</tr>
				<tr>
					<th>추가 노출카테고리</th>
					<td colspan="3">
						<input type="hidden" name="cate[]" value="<?=substr($code,0,12)?>">
						<table width="100%" cellpadding="0" cellspacing="1" id="objProductCode">
							<?
							$sql = "SELECT productcode, categorycode ";
							$sql.= "FROM tblcategorycode WHERE 1=1 ";
							$sql.= "AND productcode = '".$_data->productcode."' ";
							$sql.= "AND categorycode <> '".substr($_data->productcode,0,12)."' ";
							$sql.= "ORDER BY categorycode ASC ";
							$result = mysql_query($sql,get_db_conn());
							while($row=mysql_fetch_object($result)) {
							?>
							<tr>
								<td>
	<?
								$code_loc = "";
								$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($row->categorycode,0,3)."' ";
								if(substr($row->categorycode,3,3)!="000") {
									$sql.= "AND (codeB='".substr($row->categorycode,3,3)."' OR codeB='000') ";
									if(substr($row->categorycode,6,3)!="000") {
										$sql.= "AND (codeC='".substr($row->categorycode,6,3)."' OR codeC='000') ";
										if(substr($row->categorycode,9,3)!="000") {
											$sql.= "AND (codeD='".substr($row->categorycode,9,3)."' OR codeD='000') ";
										} else {
											$sql.= "AND codeD='000' ";
										}
									} else {
										$sql.= "AND codeC='000' ";
									}
								} else {
									$sql.= "AND codeB='000' AND codeC='000' ";
								}
								$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
								$result2=mysql_query($sql,get_db_conn());
								$i=0;
								while($row2=mysql_fetch_object($result2)) {
									if($i>0) $code_loc.= " > ";
									$code_loc.= $row2->code_name;
									$i++;
								}
								mysql_free_result($result2);
	
								echo $code_loc;
	?>
								<td>
								<td>
									<input type="hidden" name="cate[]" value="<?=$row->categorycode?>">
									<a href='javascript:void(0)' onClick='cateDel(this.parentNode.parentNode)'><input type='button' value='삭제' align=absmiddle></a>
								</td>
							</tr>
							<?
							}
							?>
						</table>
						<select name="codeA" style="width:200px" onchange="SearchChangeCate(this,1);">
							<option value="">--- 1차 카테고리 선택 ---</option>
						</SELECT>
						<select name="codeB" style="width:200px" onchange="SearchChangeCate(this,2);">
							<option value="">--- 2차 카테고리 선택 ---</option>
						</SELECT>
						<select name="codeC" style="width:200px" onchange="SearchChangeCate(this,3);">
							<option value="">--- 3차 카테고리 선택 ---</option>
						</SELECT>
						<select name="codeD" style="width:200px">
							<option value="">--- 4차 카테고리 선택 ---</option>
						</SELECT>
						<input type="button" value="추가" onclick="javascript:cateAdd()">
					</TD>
				</TR>
				<tr>
					<th>상품진열여부</th>
					<td colspan="3">
						<input type=radio id="idx_display1" name=display value="Y" <? if ($_data) { if ($_data->display=="Y") echo "checked"; } else echo "checked";  ?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display1>진열함</label>
						&nbsp;
						<input type=radio id="idx_display2" name=display value="N" <? if ($_data) { if ($_data->display=="N") echo "checked"; } ?> onclick="JavaScript:alert('메인 화면의 상품 특징이 없음으로 변경됩니다.')">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display2>진열안함</label>
					</td>
				</tr>
				<tr>
					<th class="reqFld">상품명</th>
					<td colspan="3">
						<input name=productname value="<?=ereg_replace("\"","&quot",$_data->productname)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" class="input" style="width:100%">
					</td>
				</tr>
				<tr>
					<th>상품홍보문구</th>
					<td colspan="3">
						<input name="prmsg" value="<?=ereg_replace("\"","&quot",$_data->prmsg)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" class="input" style="width:100%">
					</td>
				</tr>
			</tbody>
		</table>
		
		
		
		
		
		
		
		<table border="0" cellpadding="0" cellspacing="0" class="inputTbl rentalItemArea" style="border-top:0px;">
			<tbody>
				<?
		$usevender = getVenderUsed();
		if($usevender['OK'] == "OK"){ 	// statr rent if
		?>
				<tr>
					<th>상품관리 설정 관리</th>
					<td colspan="3">
						<?	if($_data->vender > 0){
				$commi = rentCommitionByCategory($code,$_data->vender);
				//_pr($commi);
				if($rentProduct['istrust'] == '0' && !_empty($_data->trustCommi)) $commi['main'] = $_data->trustCommi; // 적용된 수수료가 있을 경우
		?>
						<div style="margin-bottom:5px;">
							<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>   />
							셀프관리 (수수료
							<?=number_format($commi['self'])?>
							%)
							<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  />
							위탁관리 (수수료
							<input type="text" name="trustCommi" value="<?=$commi['main']?>" style="width:30px;" />
							%)
							<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> />
							위탁승인대기</div>
						<?  } ?>
							<div id="goodsTypeLocalDiv" class="rentalItemArea">
							<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
								<tr>
									<th style="width:100px;">상품 타입</th>
									<td style="text-align:left;padding-left:10px;">
										<input type=radio id="itemType1" name="itemType" value="product" <?=$itemTypeSel['product']?>>
										<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label>
										&nbsp;
										<input type=radio id="itemType2" name="itemType" value="location" <?=$itemTypeSel['location']?>>
										<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label>
										&nbsp; </td>
									<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
									<th style="width:100px;">가격구분</th>
									<td style="text-align:left;padding:0px 10px;">
										<script language="javascript" type="text/javascript">
										$j("#trust_sel").focus(function(){
											//this.initialSelect = this.selectedIndex;
										});
										$j("#trust_sel").change(function(){
											//this.selectedIndex = this.initialSelect;
										});
										
										function autoPrice(cel,del,sel){
											if(cel && del && sel){
												var customerp = parseInt($j(cel).val());
												var discountp = parseInt($j(del).val());
												var sellp = 0;
												if(!isNaN(customerp) && customerp >= 0){
													sellp = customerp;
													if(!isNaN(discountp) && discountp > 0 && discountp <=100){
														$j(del).val(discountp);
														sellp = parseInt(Math.floor(customerp*discountp/100)/100)*100;
													}else{
														$j(del).val('0');
													}
												}
												$j(sel).val(sellp);
											}else{

											}
										}

										function autoPercent(cel,del,sel){
											if(cel && del && sel){
												var customerp = parseInt($j(cel).val());
												var sellprice = parseInt($j(sel).val());
												var discper=0;
												if(!isNaN(customerp) && customerp >= 0){
													discper = $j(del).val();
													if(!isNaN(sellprice) && sellprice > 0){
														$j(sel).val(sellprice);
														discper = parseInt(Math.round(100*sellprice/customerp));
													}else{
														$j(sel).val('0');
													}
												}
												$j(del).val(discper);
											}else{
											}
										}

										function autoPercent_basic(cel,del,sel){
											if(cel && del && sel){
												var customerp = parseInt($j(cel).val());
												var sellprice = parseInt($j(sel).val());
												var discper=0;
												if(!isNaN(customerp) && customerp >= 0){
													discper = $j(del).val();
													if(!isNaN(sellprice) && sellprice > 0){
														$j(sel).val(sellprice);
														discper = 100-parseInt(Math.round(100*sellprice/customerp));
													}else{
														$j(sel).val('0');
													}
												}
												$j(del).val(discper);
											}else{
											}
										}
										
										function chPriceType(){ 
											var idx = $j("#pricetype > option:selected").val(); 
											if(idx=="day"){
												$j('#day_div').show();
												$j('#time_div').hide();
												$j('#checkout_div').hide();
												$j('#period_div').hide();
												$j('#long_div').hide();

												$j('#rentOptTable').show();
												$j('#rentOptTable2').hide();
												$j('.rentalItemArea5').show();
												$j('.rentalItemArea7').hide();
												$j('.rentalItemArea8').hide();
												$j('.rentalItemArea9').hide();
											}else if(idx=="time"){								
												$j('#day_div').hide();
												$j('#time_div').show();
												$j('#checkout_div').hide();
												$j('#period_div').hide();
												$j('#long_div').hide();

												$j('#rentOptTable').show();
												$j('#rentOptTable2').hide();
												$j('.rentalItemArea5').show();
												$j('.rentalItemArea7').hide();
												$j('.rentalItemArea8').hide();
												$j('.rentalItemArea9').hide();
											}else if(idx=="checkout"){
												$j('#day_div').hide();
												$j('#time_div').hide();
												$j('#checkout_div').show();
												$j('#period_div').hide();
												$j('#long_div').hide();
												$j('#checkin_time').val($j('#rent_stime').val());
												$j('#checkout_time').val($j('#rent_etime').val());

												$j('#rentOptTable').show();
												$j('#rentOptTable2').hide();
												$j('.rentalItemArea5').show();
												$j('.rentalItemArea7').hide();
												$j('.rentalItemArea8').hide();
												$j('.rentalItemArea9').hide();
											}else if(idx=="period"){//단기기간
												$j('#day_div').hide();
												$j('#time_div').hide();
												$j('#checkout_div').hide();
												$j('#period_div').show();
												$j('#long_div').hide();

												$j('#rentOptTable').show();
												$j('#rentOptTable2').hide();
												$j('.rentalItemArea5').hide();
												$j('.rentalItemArea7').show();
												$j('.rentalItemArea8').hide();
												$j('.rentalItemArea9').hide();
											}else if(idx=="long"){//장기기간
												$j('#day_div').hide();
												$j('#time_div').hide();
												$j('#checkout_div').hide();
												$j('#period_div').hide();
												$j('#long_div').show();
												
												$j('#rentOptTable').hide();
												$j('#rentOptTable2').show();
												$j('.rentalItemArea5').show();
												$j('.rentalItemArea7').show();
												$j('.rentalItemArea8').show();
												$j('.rentalItemArea9').show();
											}
											$j('.rentalItemArea10').show();
										}
										</script>
										<?
										//gura
										$sql = "SELECT * FROM vender_rent ";
										$sql.= "WHERE pridx='".$_data->pridx."'";

										$result=mysql_query($sql,get_db_conn());
										$_ptdata=mysql_fetch_object($result);
										mysql_free_result($result);
										
										//신규등록시 입점업체고유방식선택시 or 상품수정시
										
										if($_ptdata->pricetype!=""){
											$categoryRentInfo['rent_stime'] = $_ptdata->rent_stime;
											$categoryRentInfo['rent_etime'] = $_ptdata->rent_etime;
											$categoryRentInfo['pricetype'] = $_ptdata->pricetype;
											$categoryRentInfo['halfday'] = $_ptdata->halfday;
											$categoryRentInfo['halfday_percent'] = $_ptdata->halfday_percent;
											$categoryRentInfo['oneday_ex'] = $_ptdata->oneday_ex;
											$categoryRentInfo['time_percent'] = $_ptdata->time_percent;
											$categoryRentInfo['base_period'] = $_ptdata->base_period;
											$categoryRentInfo['ownership'] = $_ptdata->ownership;
											$categoryRentInfo['base_time'] = $_ptdata->base_time;
											$categoryRentInfo['base_price'] = $_ptdata->base_price;
											$categoryRentInfo['timeover_price'] = $_ptdata->timeover_price;
											$categoryRentInfo['checkin_time'] = $_ptdata->checkin_time;
											$categoryRentInfo['checkout_time'] = $_ptdata->checkout_time;

											$categoryRentInfo['useseason'] = $_ptdata->useseason;
											$categoryRentInfo['cancel_cont'] = $_ptdata->cancel_cont;
											$categoryRentInfo['discount_card'] = $_ptdata->discount_card;											
										}
										
										/*
										switch($_ptdata->pricetype){
											case 'time': echo '1시간제'; break;
											case 'day': echo '24시간제'; break;
											case 'checkout': echo '일일제(숙박제)'; break;
											case 'period': echo '단기기간제'; break;
											case 'long': echo '장기기간제(약정)'; break;
											default: echo '오류'; break;
										} 
										*/
									?>
										<div style="padding:5px;border-bottom:1px solid #eeeeee;text-align:left">
											<select name="pricetype" id="pricetype" onchange="javascript:chPriceType()" style="width:110px">
												<option value="day" <? if($categoryRentInfo['pricetype'] == 'day') echo ' selected="selected"'; ?> >24시간제</option>
												<option value="time" <? if($categoryRentInfo['pricetype'] == 'time') echo ' selected="selected"'; ?>>1시간제</option>
												<option value="checkout" <? if($categoryRentInfo['pricetype'] == 'checkout') echo ' selected="selected"'; ?>>일일제(숙박제)</option>
												<option value="period" <? if($categoryRentInfo['pricetype'] == 'period') echo ' selected="selected"'; ?> >단기기간제</option>
												<option value="long" <? if($categoryRentInfo['pricetype'] == 'long') echo ' selected="selected"'; ?> >장기기간제(약정)</option>
											</select>&nbsp;&nbsp;
											<span id="rent_time" style="display:<?=($categoryRentInfo['pricetype'] == 'checkout')? "none":"display"; ?>">
												시작: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$categoryRentInfo['rent_stime']?>">시 ~
												종료: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$categoryRentInfo['rent_etime']?>">시 
											</span>
										</div>
										<? if($categoryRentInfo['pricetype'] == 'day') $display = ""; else $display = "none"; ?>
										<table id="day_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:7px 7px 7px 7px;">
											<tr>
												<th style="width:120px;">당일 12시간 대여허용</th>
												<td class="norbl" style="padding:5px;text-align:left">
													<input type=radio name=halfday value="Y" <?if($_ptdata->halfday=="Y")echo"checked";?>>예<br>
													<input type=radio name=halfday value="N" <?if($_ptdata->halfday=="N")echo"checked";?>>아니오
												</td>
												<!--td id="price1">
													<?
													/*
													if($_ptdata->halfday=="Y"){
														echo '<div>당일 12시간 요금: <br>';
														echo '24시간 요금의 <input type="text" name="halfday_percent" class="autoSolv" size="3" maxlength="2" value="'.$_ptdata->halfday_percent.'">%';
														echo '<input type="text" name="halfday_per_price" size="5" class="autoPerSolv" value="">원</div>';

													}else{
														$halfday_percent = 70;
													}*/
													?>
												</td-->
											</tr>
											<tr>
												<th>1일 초과시 과금기준</th>
												<td class="norbl" style="padding:5px;text-align:left">
													<input type=radio name=oneday_ex value="day" <?if($_ptdata->oneday_ex=="day")echo"checked";?>>1일 단위<br>
													<input type=radio name=oneday_ex value="half" <?if($_ptdata->oneday_ex=="half")echo"checked";?>>12시간 단위<br>
													<input type=radio name=oneday_ex value="time" <?if($_ptdata->oneday_ex=="time")echo"checked";?>>1시간 단위
												</td>
												<!--td id="price2">
													<?
													/*
													if($_ptdata->oneday_ex=="time"){
														echo '<div>추가 1시간 요금: ';
														echo '<br>24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'" class="autoSolv">%';
														echo '<input type="text" name="time_per_price" class="autoPerSolv" size="5" value="">원</div>';
													}else if($_ptdata->oneday_ex=="half"){
														echo '<div>추가 12시간 요금: ';
														echo '<br>24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'" class="autoSolv">%';
														echo '<input type="text" name="time_per_price" class="autoPerSolv" size="5" value="">원</div>';
													}
													*/
													?>
												</td-->
											</tr>
										</table>

										<? if($categoryRentInfo['pricetype'] == 'time') $display = ""; else $display = "none"; ?>
										<table id="time_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
											<tr>
												<th style="width:80px;">기본요금</th>
												<td class="norbl" style="text-align:right;padding-right:5px">
													최소시간 <select name="base_time" onchange="javascript:changePrice2();" style="width:60px">>
														<? for($i=1;$i<=36;$i++){?>
														<option value="<?=$i?>" <? if($_ptdata->base_time == $i) echo ' selected="selected"'; ?> ><?=$i?>시간</option>
														<? } ?>
													</select> <!--<input type="text" name="base_price" size="10" value="<?=$_ptdata->base_price?>" onkeyup="javascript:changePrice();"> 원-->
												</td>
											</tr>
											<!--tr>
												<th>초과 1시간당</th>
												<td style="text-align:right;padding-right:5px"><input type="text" name="timeover_price" size="10" value="<?=$_ptdata->timeover_price?>" readonly> 원</td>
											</tr-->
										</table>

										<? if($categoryRentInfo['pricetype'] == 'checkout') $display = ""; else $display = "none"; ?>
										<table id="checkout_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:7px">
											<tr>
												<th style="width:80px;">체크인</th>
												<td class="norbl" style="padding:5px;text-align:left">
													<select name="checkin_time" style="width:50px">
														<? for($i=0;$i<=23;$i++){ ?>
														<option value="<?=sprintf('%02d',$i)?>" <? if($_ptdata->checkin_time==sprintf('%02d',$i)){echo "selected";}?>><?=sprintf('%02d',$i)?>시</option>
														<? } ?>
													</select>
												</td>
												<th style="width:80px;">체크아웃</th>
												<td style="padding:5px;text-align:left">
													<select name="checkout_time" style="width:50px">
														<? for($i=0;$i<=23;$i++){ ?>
														<option value="<?=sprintf('%02d',$i)?>" <? if($_ptdata->checkout_time==sprintf('%02d',$i)){echo "selected";}?>><?=sprintf('%02d',$i)?>시</option>
														<? } ?>
													</select>
												</td>
											</tr>
										</table>

										<? if($categoryRentInfo['pricetype'] == 'period') $display = ""; else $display = "none"; ?>
										<table id="period_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
											<tr>
												<th style="width:100px;">기본대여일</th>
												<td class="norbl" style="padding:5px;">
													<input type="text" name="base_period" size="5" value="<?=$categoryRentInfo['base_period']?>" onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);$j('#bp_text').text('*'+$j('input[name=base_period]').val()+'일은 '+(parseInt($j('input[name=base_period]').val())-1)+'박 '+$j('input[name=base_period]').val()+'일 입니다.')">일 
													&nbsp;&nbsp;<span id="bp_text">*<?=$categoryRentInfo['base_period']?>일은 <?=$categoryRentInfo['base_period']-1?>박 <?=$categoryRentInfo['base_period']?>일입니다.</span>
												</td>
											</tr>
										</table>

										<? if($categoryRentInfo['pricetype'] == 'long') $display = ""; else $display = "none"; ?>
										<table id="long_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
											<tr>
												<th style="width:100px;">만기 후 소유권</th>
												<td class="norbl" style="padding:5px;">
													<input type=radio name="ownership" value="mv" <?if($categoryRentInfo['ownership']=="mv")echo"checked";?>>이전 
													<input type=radio name="ownership" value="re" <?if($categoryRentInfo['ownership']=="re")echo"checked";?>>반납
												</td>
											</tr>
										</table>
									</td>
									<? } ?>
									<th style="width:120px;">성수기사용</th>
									<td style="text-align:left;padding:0px 10px;">
										<? //echo ($categoryRentInfo['useseason'] == '1')?'성수기 사용':'사용안함';?>
										<input type=radio name=useseason value="0" <?if($categoryRentInfo['useseason']!="1")echo"checked";?> onclick="toggleSeasonList()">사용안함 <br>
										<input type=radio name=useseason value="1" <?if($categoryRentInfo['useseason']=="1")echo"checked";?> onclick="toggleSeasonList()">(비)성수기 사용
										<div id="seasonDiv"> 
											<script language="javascript" type="text/javascript">
											function toggleSeasonList(){
												var f = document.form1;
												var listdisp = false;
												for(i=0;i<f.useseason.length;i++){
													if(f.useseason[i].checked){
														if(f.useseason[i].value == '1') listdisp = true;
														break;
													}
												}
												document.getElementById('seasonListTbl').style.display = (listdisp)?'block':'none';
												if(listdisp){
													$j('.seasonList').show();
													$j('.itemSepTD').attr('rowspan',6);
												}else{
													$j('.seasonList').hide();
													$j('.itemSepTD').attr('rowspan',1);
												}
												toggleOptType();
											}
											</script>
											<? if($categoryRentInfo['useseason'] == '1') $display = ""; else $display = "none"; ?>
											<? if(!_isInt($_data->pridx)){ ?>
											<div id="seasonListTbl" style="font-weight:bold; padding:10px 0px;display:<?=$display?>"> 성수기/비성수기 설정은 상품 신규 등록 후 내 상품관리 에서 수정이 가능합니다.</div>
											<? }else{ ?>
											<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" class="infoListTbl" style="margin-top:7px;display:<?=$display?>;margin-top:5px;border:1px solid #efefefe">
												</tr>
													<td class="norbl" style="padding:5px;">
														<input type="button" value="성수기/준성수기 관리" style="width:200px;" onclick="window.open('../vender/vender_seasonpop.php?vender=<?=$_data->vender?>&pridx=<?=$_data->pridx?>', 'busySeasonPop', 'width=800,height=600' );">
													</td>
												</tr>
												<tr>
													<td style="padding:5px;" class="norbl nobbl">
														<input type="button" value="공휴일/주말 관리" style="width:200px;"  onclick="window.open('../vender/vender_holiday.php?vender=<?=$_data->vender?>&pridx=<?=$_data->pridx?>', 'holidayPop', 'width=800,height=600' );">
													</td>
												</tr>
											</table>
											<? } ?>											
										</div>
										
									</td>
								</tr>
							</table>
							<div>
							<? if(false && _isInt($_data->pridx)){ ?>
								<input type="button" value="예약/렌탈 현황보기" onclick="bookingSchedulePop(<?=$_data->pridx?>,'1');">
								<input type="button" value="정비입고" onclick="bookingRepair(<?=$_data->pridx?>);">							
							<? } ?>
						</div>
					</td>
				</tr>
				<tr>
					<th>출고지 관리</th>
					<td colspan="3">
						<?
				// 대여 출고지 정보 리스트	
				$value = array("display"=>1,'vender'=>(($rentProduct['istrust'] == '1')?$_data->vender:0)); // 노출 만 표시
				$localList = rentLocalList( $value );
				if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;								
				?>
						<select name="location">
							<option value="0"  <? if($rentProduct['location'] == 0) echo 'selected="selected"';?>>-- 출고지 선택 --</option>
							<? foreach ( $localList as $k=>$v ) { ?>
							<option value="<?=$v['location']?>"  <? if($rentProduct['location'] == $v['location']) echo 'selected="selected"';?>>
							<? /* [ <?=rentProduct::locationType($v['type'])?> ]*/ ?><?=$v['title']?>
							</option>
							<? } ?>
						</select>
						<span class="localReloadBtn" style="border:1px solid #e3e3e3; background:#efefef; padding:3px">출고지갱신</span>
						<span class="localEditBtn" style="border:1px solid #e3e3e3; background:#efefef; padding:3px">출고지 관리</span>
						<br />
						<span class="font_orange">* 출고지 관리를 통해 출고지 정보를 변경 하신 경우 출고지갱신 버튼을 통해서 목록을 갱신해 주세요.</span>
						<script language="javascript" type="text/javascript">
							function openLocal(){
								window.open('/admin/product_rental.local.php?ispop','Location','width=800,height=600');
							}
							
							function reloadLocation(){
								$j('select[name=location]').find('option:gt(0)').remove();
								$j('select[name=location]').find('option:eq(0)').text('-- 목록 갱신중 --');
								
								$j.post('/lib/ext/getbyjson.php',{'act':'getLocallist','vender':'<?=$value ?>'},function(data){
									if(data.err != 'ok'){
										$j('select[name=location]').find('option:eq(0)').text('-- 목록 갱신 오류 --');
										alert(data.err);
									}else{
										$j('select[name=location]').find('option:eq(0)').text('-- 출고지 선택 --');
										//alert(data.items.length);
										if(data.items.length > 0){
											$j.each(data.items,function(idx,itm){
												str = '<option value="'+itm.location+'"';
												if('<?=$rentProduct['location']?>' == itm.location) str += ' selected="selected"';
												str += '>';
												str +=itm.title+'</option>';
												$j('select[name=location]').append(str);												
											});											
										}
									}
								},'json');
							}
						
							$j(function(){
								$j('.localReloadBtn').on('click',function(e){
									e.preventDefault;
									reloadLocation();
								});
								$j('.localEditBtn').on('click',function(e){
									e.preventDefault;
									openLocal();
								});
								$j('.localReloadBtn,.localEditBtn').css('cursor','pointer');
								
								
								
							});
						</script>
					</td>
				</tr>				
				<?	if($_data->vender > 0){  ?>
				<tr>
					<th>노출아이디선택</th>
					<td colspan="3">
						<? if($rentProduct['istrust'] == '1') echo '본인';
			else{ ?>
						<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />
						본인
						<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />
						잠깐본점
						<?	}?>
					</td>
				</tr>
				<TR>
					<th>미니홈노출</th>
					<td colspan="3">
						<? if($rentProduct['istrust'] == '1') echo '예';
			else{ ?>
						<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />
						예
						<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />
						아니오
						<?	}?>
					</td>
				</tr>
				<?  } // end rent if?>
				<tr>
					<th>당일예약가능여부</th>	
						
					<td colspan="3">
						<input type="radio" name="today_reserve" id="itemReserve1" value="Y" <?=($_data->today_reserve == 'Y')?'checked':''?> />
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve1>가능</label>
						<input type="radio" name="today_reserve" id="itemReserve2" value="N" <?=($_data->today_reserve == 'N')?'checked':''?> />
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve2>불가능</label>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" class="inputTbl" style="border-top:0px;">
			<tbody>
				<? /*
				<tr>
					<th>상품 등록날짜</th>
					<td colspan="3">
						<input type=checkbox id="idx_insertdate10" name=insertdate1 value="Y" onclick="DateFixAll(this)" <?=($insertdate_cook=="Y")?"checked":"";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate10>등록 일자 고정</label>
						&nbsp;<span class="font_orange">(* 상품수정시 등록날짜가 변경되지 않습니다.)</span></td>
				</tr>*/ ?>
				<!-- <tr>
					<th>혜택 및 구매제한</th>
					<td colspan="3">
						<input type=checkbox id="idx_etcapply_coupon" name=etcapply_coupon value="Y" <?=($_data->etcapply_coupon=="Y")?"checked":"";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_coupon>할인쿠폰 적용불가</label>
						&nbsp;&nbsp;&nbsp;
						<input type=checkbox id="idx_etcapply_reserve" name=etcapply_reserve value="Y" <?=($_data->etcapply_reserve=="Y")?"checked":"";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_reserve>적립금 사용불가</label>
						&nbsp;&nbsp;&nbsp;
						<input type=checkbox id="idx_etcapply_gift" name=etcapply_gift value="Y" <?=($_data->etcapply_gift=="Y")?"checked":"";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_gift>구매사은품 적용불가</label>
						<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($_data->etcapply_return=="Y")?"checked":"";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>교환 및 환불 불가</label>
						<input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>현금결제만 사용하기</label>
						<span class="font_orange">(여러 상품과 함께 구매시 결제는 현금결제로만 진행됩니다.)</span></td>
				</tr> -->
				<tr>
					<th>
						<input type=hidden name="assembleuse" value="<?=$_data->assembleuse?>">
						<?	if($_data->assembleuse=="Y") { ?>
						
						<!-- <span class="font_orange"><b><?=($gongtype=="Y"?"코디/조립 판매가":"코디/조립 판매가")?></b></span> -->
						
						<? } else { ?>
						<span class="font_orange" style="font-weight:bold"><span class="productItemArea">판매가격</span><span class="rentalItemArea">대여가격</span></span>
						<? } ?>
					</th>
					<td colspan="3">
					<style type="text/css">
					#rentOptTable{border-top:1px solid #b9b9b9;font-size:12px;}
					#rentOptTable th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8; background-image:none; text-align:center}
					#rentOptTable .firstTh{border-left:none;background:#f8f8f8;}
					#rentOptTable td{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
					#rentOptTable .firstTd{padding-left:10px;border-left:none;}
					</style>					
						<div class="productItemArea">
						판매가 :
						<input name=sellprice value="<?=(int)(strlen($_data->sellprice)>0?$_data->sellprice:"0")?>" size=16 maxlength=10 class="input" <?=($_data->assembleuse=="Y"?"disabled style='background:#C0C0C0'":"")?> style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('sell');" onfocus="sellpriceAutoCalc('sell');">
						원
						=
						정상가 :
						<input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('org');" onfocus="sellpriceAutoCalc('org');" >
						원
						-
						할인율 :
						<input name=discountRate value="<?=(int)(strlen($_data->discountRate)>0?$_data->discountRate:"0")?>" size=3 maxlength=3 class="input" style="text-align:center; font-weight:bold; width:40px;" onkeyup="sellpriceAutoCalc('disc');">
						%					
						</div>
						
						<div class="rentalItemArea" id="rentPriceArea">
						<script language="javascript" type="text/javascript">
						function toggleOptType(){
							<? if(!_isInt($rentProduct['pridx'])){ ?>
							var mopttype = $j('input:radio[name=multiOpt]:checked').val();
							<? }else{ ?>
							var mopttype = '<?=($rentProduct['multiOpt']=='1')?'1':'0'?>';
							<? } ?>
							var ownership = $j('input:radio[name=ownership]:checked').val();
							var pricetype = $j('select[name=pricetype]').val();
							var halfday = $j('input:radio[name=halfday]:checked').val();
							var oneday_ex = $j('input:radio[name=oneday_ex]:checked').val();
							
							/*
							1시간제인 경우 옵션별 초과요금설정
							*/
							if(pricetype=="time"){
								$j('#rentOptTable').find('.optTime').css('display','');
							}else{
								$j('#rentOptTable').find('.optTime').css('display','none');
							}

							/*
							24시간제인 경우 옵션별 12시간요금설정
							*/
							if(pricetype=="day"){
								if(halfday=="Y"){
									$j('#rentOptTable').find('.optDay').css('display','');
								}else{
									$j('#rentOptTable').find('.optDay').css('display','none');
								}
								if(oneday_ex=="half"){
									$j('#rentOptTable').find('.optDay2').css('display','');
									$j('#rentOptTable').find('.optDay3').css('display','none');
								}else if(oneday_ex=="time"){
									$j('#rentOptTable').find('.optDay2').css('display','none');
									$j('#rentOptTable').find('.optDay3').css('display','');
								}else{
									$j('#rentOptTable').find('.optDay2').css('display','none');
									$j('#rentOptTable').find('.optDay3').css('display','none');
								}
								
							}else{
								$j('#rentOptTable').find('.optDay').css('display','none');
								$j('#rentOptTable').find('.optDay2').css('display','none');
								$j('#rentOptTable').find('.optDay3').css('display','none');
							}
							
							if(mopttype == '1'){
								$j('#rentOptTable').find('.optMulti').css('display','');
								$j('#rentOptTable').find('caption').css('display','');
								
								$j('#rentOptTable2').find('.optMulti').css('display','');
								$j('#rentOptTable2').find('caption').css('display','');
								if(ownership=="mv"){
									$j('#rentOptTable2').find('.optMoney').css('display','none');
								}else{
									$j('#rentOptTable2').find('.optMoney').css('display','');
								}
							}else{
								$j('#rentOptTable').find('.optMulti').css('display','none');
								$j('#rentOptTable').find('caption').css('display','none');
								
								$j('#rentOptTable2').find('.optMulti').css('display','none');
								$j('#rentOptTable2').find('caption').css('display','none');
							
								if(ownership=="mv"){
									$j('#rentOptTable2').find('.optMoney').css('display','none');
								}else{
									$j('#rentOptTable2').find('.optMoney').css('display','');
								}
								//$j('#rentOptTable>tbody').find('tr:gt(0)').remove();
							}
							syncmopttype = mopttype;
							syncownership = ownership;
							if(pricetype!="long"){
								if($j('#rentOptTable>tbody').find('tr').length < 1){
									rentOptInsert(true);
									$j('#rentOptTable2>tbody').find('tr').remove();
								}
							}else{
								if($j('#rentOptTable2>tbody').find('tr').length < 1){
									rentOptInsert2(true);
									$j('#rentOptTable>tbody').find('tr').remove();
								}
							}
							/*
							if(mopttype == '1'){
								$j('#rentOptTable').find('.optMulti').css('display','');
								$j('#rentOptTable').find('caption').css('display','');
							}else{
								$j('#rentOptTable').find('.optMulti').css('display','none');
								$j('#rentOptTable').find('caption').css('display','none');
								//$j('#rentOptTable>tbody').find('tr:gt(0)').remove();
							}
							if($j('#rentOptTable>tbody').find('tr').length < 1){
								rentOptInsert(true);
							}		
							*/
						}
						function delOptitem(el){							
							var cnt = 	$('#rentOptTable>tbody').find('tr.itemSepRow').length;
							if(cnt < 2){
								alert('1개 이상의 옵션 항목이 필요 합니다.');
							}else{
								var pel = $j(el).parent().parent();
								$j(pel).nextUntil('.itemSepRow','tr').remove();
								$j(pel).remove();
							}
						}
						
						function rentOptInsert(){
							bel = $j('#rentPriceArea').find('textarea[name=optformatcode]');
							$j('#rentOptTable>tbody').append($j(bel).val());
							toggleSeasonList();
							if(!chktoggle) toggleOptType();
							else{
								if(syncmopttype != '1'){
									$j('#rentOptTable').find('.optMulti').css('display','none');
									$j('#rentOptTable').find('caption').css('display','none');
								}
							}
						}

						function delOptitem2(el){							
							var cnt = 	$('#rentOptTable2>tbody').find('tr.itemSepRow').length;
							if(cnt < 2){
								alert('1개 이상의 옵션 항목이 필요 합니다.');
							}else{
								var pel = $j(el).parent().parent();
								$j(pel).nextUntil('.itemSepRow','tr').remove();
								$j(pel).remove();
							}
						}
						function rentOptInsert2(chktoggle){
							bel = $j('#rentPriceArea').find('textarea[name=optformatcode2]');
							$j('#rentOptTable2>tbody').append($j(bel).val());
							if(!chktoggle) toggleOptType();
							else{
								if(syncmopttype != '1'){
									$j('#rentOptTable2').find('.optMulti').css('display','none');
									$j('#rentOptTable2').find('caption').css('display','none');
								}
								if(syncownership=="mv"){
									$j('#rentOptTable2').find('.optMoney').css('display','none');
								}else{
									$j('#rentOptTable2').find('.optMoney').css('display','');
								}
							}
						}
						
						/*
						function autoSolv(cel,del,sel,v){
							if(cel && del && sel){
								var customerp = parseInt($j(cel).val());
								var discountp = parseInt($j(del).val());
								var sellprice = parseInt($j(sel).val());
								var sellp = 0;
								var disp = 0;
								if(v == 'disc'){
									if(!isNaN(discountp) && discountp >= 0){
										$j(del).val(discountp);
										sellp = parseInt(Math.round(customerp*(100-discountp)/10)/100)*10;
									} else {
										$j(del).val('0');
									}

									if(sellp != 0){
										$j(sel).val(sellp);
									} else {
										$j(sel).val(customerp);
									}
								} else if (v == 'sell') {
									if(!isNaN(sellprice) && sellprice >= 0){
										$j(sel).val(sellprice);
										disp = parseInt(100 - ( ( sellprice / customerp ) * 100) );
										$j(del).val(disp);
									} else {
										$(sel).val('0');
									}
									
									$j(del).val(disp);
								}
							}else{

							}
						}
						*/

						function autoSolv(cel,del,sel){
							if(cel && del && sel){
								var customerp = parseInt($j(cel).val());
								var discountp = parseInt($j(del).val());
								var sellp = 0;						
								if(!isNaN(customerp) && customerp >= 0){
									sellp = customerp;
									if(!isNaN(discountp) && discountp > 0 && discountp <100){
										$j(del).val(discountp);
										sellp = parseInt(Math.round(customerp*(100-discountp)/10)/100)*10;
									}else{
										$j(del).val('0');
									}
								}
								$j(sel).val(sellp);
							}else{
							}
						}

						function autoSolv2(mon,opy,sel,ins){
							if(mon && (opy || sel) && ins){
								var month = parseInt($j(mon).val());
								var optpay = $j(opy).val();
								var customerp = parseInt($j(sel).val());
								var intsp = parseInt($j(ins).val());

								if(optpay=="분납"){
									if(!isNaN(customerp) && customerp > 0){
										intsp = parseInt(customerp/month);
										$j(ins).val(intsp);
									}	
								}
							}
						}

						function autoSolv3(mon,opy,sel,ins){
							if(mon && (opy || sel) && ins){
								var month = parseInt($j(mon).val());
								var optpay = $j(opy).val();
								var customerp = parseInt($j(sel).val());
								var intsp = parseInt($j(ins).val());

								if(optpay=="분납"){
									if(!isNaN(intsp) && intsp > 0){
										customerp = parseInt(intsp*month);
										$j(sel).val(customerp);
									}	
								}
							}
						}
						
						/*
						$j(function(){
							$j('input:radio[name=multiOpt]').on('click',toggleOptType);							
							$j('#rentOptTable').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent().parent();
								var v   = $j(this).data("calc");
								cel = $j(ptr).find('input[name^=custPrice]');
								del = $j(ptr).find('input[name^=priceDiscP]');
								sel = $j(ptr).find('input[name^=nomalPrice]');
								autoSolv(cel,del,sel,v);
							});
							toggleOptType();
							
							$j('#rentOptTable').on('mouseover','.priceHelp',function(){
								var pos = $j(this).position();									
								var pricev = parseInt($j(this).parent().find('input[name^=nomalPrice]').val());
								if(!isNaN(pricev)){									
									if($j('#priceHelpDiv').find('#priceHelp24')) $j('#priceHelpDiv').find('#priceHelp24').text(pricev+'원');
									if($j('#priceHelpDiv').find('#priceHelp12')) $j('#priceHelpDiv').find('#priceHelp12').text(Math.round(pricev*0.7)+'원');
									if($j('#priceHelpDiv').find('#priceHelp1')) $j('#priceHelpDiv').find('#priceHelp1').text(Math.round(pricev/20)+'원');
									$j('#priceHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});
								}
							});
							
							$j('#rentOptTable').on('mouseout','.priceHelp',function(){
								$j('#priceHelpDiv').css('display','none');
							});

						});
						*/
						$j(function(){
							$j('input:radio[name=ownership]').on('click',toggleOptType);
							$j('input:radio[name=multiOpt]').on('click',toggleOptType);
							$j('#pricetype').on('change',toggleOptType);
							$j('input:radio[name=halfday]').on('click',toggleOptType);
							$j('input:radio[name=oneday_ex]').on('click',toggleOptType);

							$j('#rentOptTable').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent().parent();
								//var v   = $j(this).data("calc");
								cel = $j(ptr).find('input[name^=custPrice]');
								del = $j(ptr).find('input[name^=priceDiscP]');
								sel = $j(ptr).find('input[name^=nomalPrice]');

								$('input[name=base_price]').val(cel.val());
								autoSolv(cel,del,sel);
							});
							
							//초과 1시간당
							$j('#rentOptTable').on('keyup','.autoSolv_time',function(e){
								var ptr = $j(this).parent().parent();
								cel4 = $j(ptr).find('input[name^=nomalPrice]');
								del4 = $j(ptr).find('input[name^=productTimeover_percent]');
								sel4 = $j(ptr).find('input[name^=productTimeover_price]');
								autoPrice(cel4,del4,sel4);
							});
							
							//당일12시간요금
							$j('#rentOptTable').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent().parent();
								var ptr2 = $j('#price1').parent();
								del2 = $j(ptr2).find('input[name^=halfday_percent]');
								cel2 = $j(ptr).find('input[name^=nomalPrice]');
								sel2 = $j(ptr2).find('input[name^=halfday_per_price]');
								autoPrice(cel2,del2,sel2);
							});
							
							//추가12시간요금
							$j('#rentOptTable').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent().parent();
								var ptr3 = $j('#price2').parent();
								del3 = $j(ptr3).find('input[name^=time_percent]');
								sel3 = $j(ptr3).find('input[name^=time_per_price]');
								autoPrice(cel2,del3,sel3);
							});

							//추가1시간요금
							$j('#rentOptTable').on('keyup','.autoSolv_half',function(e){
								var ptr = $j(this).parent().parent();
								cel5 = $j(ptr).find('input[name^=nomalPrice]');
								del5 = $j(ptr).find('input[name^=productHalfday_percent]');
								sel5 = $j(ptr).find('input[name^=productHalfday_price]');
								autoPrice(cel5,del5,sel5);
							});
							
							$j('#rentOptTable').on('keyup','.autoSolv_halftime',function(e){
								var ptr = $j(this).parent().parent();
								cel6 = $j(ptr).find('input[name^=nomalPrice]');
								del6 = $j(ptr).find('input[name^=productOverHalfTime_percent]');
								sel6 = $j(ptr).find('input[name^=productOverHalfTime_price]');
								autoPrice(cel6,del6,sel6);
							});
							
							$j('#rentOptTable').on('keyup','.autoSolv_onetime',function(e){
								var ptr = $j(this).parent().parent();
								cel7 = $j(ptr).find('input[name^=nomalPrice]');
								del7 = $j(ptr).find('input[name^=productOverOneTime_percent]');
								sel7 = $j(ptr).find('input[name^=productOverOneTime_price]');
								autoPrice(cel7,del7,sel7);
							});

							$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent().parent();
								cel0 = $j(ptr).find('input[name^=custPrice]');
								del0 = $j(ptr).find('input[name^=priceDiscP]');
								sel0 = $j(ptr).find('input[name^=nomalPrice]');
								autoPercent_basic(cel0,del0,sel0);
							});
							
							$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent().parent();
								del = $j(ptr).find('input[name^=productTimeover_percent]');
								cel = $j(ptr).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=productTimeover_price]');
								autoPercent(cel,del,sel);
							});
							
							$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent().parent();
								cel5 = $j(ptr).find('input[name^=nomalPrice]');
								del5 = $j(ptr).find('input[name^=productHalfday_percent]');
								sel5 = $j(ptr).find('input[name^=productHalfday_price]');
								autoPercent(cel5,del5,sel5);
							});
							
							$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent().parent();
								cel6 = $j(ptr).find('input[name^=nomalPrice]');
								del6 = $j(ptr).find('input[name^=productOverHalfTime_percent]');
								sel6 = $j(ptr).find('input[name^=productOverHalfTime_price]');
								autoPercent(cel6,del6,sel6);
							});
							
							$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent().parent();
								cel7 = $j(ptr).find('input[name^=nomalPrice]');
								del7 = $j(ptr).find('input[name^=productOverOneTime_percent]');
								sel7 = $j(ptr).find('input[name^=productOverOneTime_price]');
								autoPercent(cel7,del7,sel7);
							});

							$j('#rentOptTable2').on('keyup','.autoSolv2',function(e){
								var ptr = $j(this).parent().parent();
								mon = $j(ptr).find('input[name^=optionName]');
								opy = $j(ptr).find('select[name^=optionPay]');
								sel = $j(ptr).find('input[name^=nomalPrice]');
								ins = $j(ptr).find('input[name^=installmentPay]');

								autoSolv2(mon,opy,sel,ins);
								$j(ptr).find("#installmentMonth").text(mon.val());
							});
							$j('#rentOptTable2').on('keyup','.autoSolv3',function(e){
								var ptr = $j(this).parent().parent().parent();
								mon = $j(ptr).find('input[name^=optionName]');
								opy = $j(ptr).find('select[name^=optionPay]');
								sel = $j(ptr).find('input[name^=nomalPrice]');
								ins = $j(ptr).find('input[name^=installmentPay]');

								autoSolv3(mon,opy,sel,ins);
								$j(ptr).find("#installmentMonth").text(mon.val());
							});
							toggleOptType();
							chPriceType();
							
							$j('#rentOptTable').on('mouseover','.priceHelp',function(){
								var pos = $j(this).position();									
								var pricev = parseInt($j(this).parent().find('input[name^=nomalPrice]').val());
								if(!isNaN(pricev)){									
									if($j('#priceHelpDiv').find('#priceHelp24')) $j('#priceHelpDiv').find('#priceHelp24').text(pricev+'원');
									if($j('#priceHelpDiv').find('#priceHelp12')) $j('#priceHelpDiv').find('#priceHelp12').text(Math.round(pricev*<?=$_ptdata->halfday_percent*0.01?>)+'원 (24시간 대여가의 <?=$_ptdata->halfday_percent?>%)');
									//if($j('#priceHelpDiv').find('#priceHelp1')) $j('#priceHelpDiv').find('#priceHelp1').text(Math.round(pricev/20)+'원');
									if($j('#priceHelpDiv').find('#priceHelp1')) $j('#priceHelpDiv').find('#priceHelp1').text(Math.round(pricev*<?=$_ptdata->time_percent*0.01?>)+'원');
									$j('#priceHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});
								}
							});

							$j('#rentOptTable').on('mouseout','.priceHelp',function(){
								$j('#priceHelpDiv').css('display','none');
							});

							$j('#rentOptTable2').on('change','.optpay',function(){
								if($j(this).val()=="분납"){
									$j(this).parent().parent().find("#instDiv").show();
								}else{
									$j(this).parent().parent().find("#instDiv").hide();
								}
							});

						});
						</script>
						<? if(!_isInt($_data->pridx)){ ?>
						<input type="radio" name="multiOpt" id="multiOpt1" value="0" checked="checked" /> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=multiOpt1>단일상품</label> 
						<input type="radio" name="multiOpt" id="multiOpt2" value="1" /><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=multiOpt2>복합상품</label><br />
						<? }else{ ?>
						<input type="hidden" name="multiOpt" value="<?=$rentProduct['multiOpt']?>" />						
						<? } ?>
						</script>						
						
						<div id="priceHelpDiv" style="width:200px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
						24시간 : <span id="priceHelp24"></span><br />
						12시간 : <span id="priceHelp12"></span><br />
						<? if($categoryRentInfo['pricetype'] == 'time'){ ?> 추가 1시간 : <span id="priceHelp1"></span><? } ?>
						</div>
						
						<?
						if($categoryRentInfo['pricetype'] != 'long'){
							$optTb1_display = "";
							$optTb2_display = "none";
							$optTb1_disabled = "";
							$optTb2_disabled = "disabled";
						}else{
							$optTb1_display = "none";
							$optTb2_display = "";
							$optTb1_disabled = "disabled";
							$optTb2_disabled = "";
						}
						?>
						<table border="0" cellpadding="0" cellspacing="0" id="rentOptTable" style="display:<?=$optTb1_display?>">
							<caption style="padding:0px;"><input type="button" value="항목 추가" onclick="javascript:rentOptInsert()" style="width:100%" /></caption>							
							<thead>
								<tr>
									<th class="firstTh optMulti">옵션명</th>
									<th>등급</th>
									<th>&nbsp;</th>
									<th>정상가-할인율</th>
									<th>= 할인가(할증)</th>
									<th class="optTime">초과 1시간당</th>
									<th class="optDay">당일12시간요금<br>(24시간요금의)</th>
									<th class="optDay2">추가12시간요금<br>(24시간요금의)</th>
									<th class="optDay3">추가1시간요금<br>(24시간요금의)</th>
									<th>재고량</th>
									<th class="optMulti">비고</th>
								</tr>	
							</thead>
							<tbody>
							<?
							$roptions = rentProduct::getoptions($_data->pridx);
							if(_array($roptions)){
								foreach($roptions as $roidx=>$roption){
							?>
								<tr class="itemSepRow">
									<td class="firstTd optMulti itemSepTD" align="center" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="optionName[]" style="width:90%;" class="input" value="<?=$roption['optionName']?>" <?=$optTb1_disabled?> /></td>
									<td align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
										<select name="optionGrade[]" <?=$optTb1_disabled?>>
											<?
											foreach (rentProduct::_status() as $k=>$v) {
												$sel = $k==$roption['grade']?'selected':'';
												echo "<option value='".$k."' ".$sel.">".$v."</option>";
											}
											?>
										</select>
									</td>				
									<td align="center">일반가(24시간)</td>
									<td style="text-align:center"><input type="text" name="custPrice[]" style="width:80px" value="<?=$roption['custPrice']?>" class="input autoSolv" <?=$optTb1_disabled?> />원 - <input type="text" name="priceDiscP[]" style="width:30px" class="input autoSolv" value="<?=$roption['priceDiscP']?>" <?=$optTb1_disabled?> />%</td>
									<td style="text-align:center"><input type="text" name="nomalPrice[]" style="width:80px;" class="input autoPerSolv" value="<?=$roption['nomalPrice']?>" <?=$optTb1_disabled?>/>원 <input type="button" class="priceHelp" style="width:30px;" value="?" /><input type="hidden" name="roptidx[]" value="<?=$roidx?>" <?=$optTb1_disabled?> /></td>
									<td align="center" class="optTime itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
										<input type="text" name="productTimeover_percent[]" value="<?=$roption['productTimeover_percent']?>" style="width:30px;" class="input autoSolv_time">% 
										<input type="text" name="productTimeover_price[]" value="<?=$roption['productTimeover_price']?>" style="width:70px;" class="input autoPerSolv">원
									</td>
									<td align="center" class="optDay itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
										<input type="text" name="productHalfday_percent[]" value="<?=$roption['productHalfday_percent']?>" style="width:30px;" class="input autoSolv_half">% 
										<input type="text" name="productHalfday_price[]" value="<?=$roption['productHalfday_price']?>" style="width:70px;" class="input autoPerSolv">원
									</td>
									<td align="center" class="optDay2 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
										<input type="text" name="productOverHalfTime_percent[]" value="<?=$roption['productOverHalfTime_percent']?>" style="width:30px;" class="input autoSolv_halftime">% 
										<input type="text" name="productOverHalfTime_price[]" value="<?=$roption['productOverHalfTime_price']?>" style="width:70px;" class="input autoPerSolv">원
									</td>
									<td align="center" class="optDay3 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
										<input type="text" name="productOverOneTime_percent[]" value="<?=$roption['productOverOneTime_percent']?>" style="width:30px;" class="input autoSolv_onetime">% 
										<input type="text" name="productOverOneTime_price[]" value="<?=$roption['productOverOneTime_price']?>" style="width:70px;" class="input autoPerSolv">원
									</td>
									<td align="center" class="itemSepTD rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="productCount[]" style="width:60px;text-align:center;" class="input" value="<?=$roption['productCount']?>" <?=$optTb1_disabled?> />개</td>
									<td align="center" class="optMulti itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<input type="button" value="삭제" onclick="javascript:delOptitem(this)" />
									<!-- <a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a> ---></td>
								</tr>								
								<tr class="seasonList" style="display:<?=$display?>">
									<td align="center">성수기*평일 할증</td>			
									<td>&nbsp;</td>
									<td style="text-align:center"><input type="text" name="busySeason[]" style="width:30px" class="input" value="<?=$roption['busySeason']?>" <?=$optTb1_disabled?> />%</td>
								</tr>
								<tr class="seasonList" style="display:<?=$display?>">
									<td align="center">성수기*주말공휴일 할증</td>			
									<td>&nbsp;</td>
									<td style="text-align:center"><input type="text" name="busyHolidaySeason[]" style="width:30px" class="input" value="<?=$roption['busyHolidaySeason']?>" <?=$optTb1_disabled?> />%</td>
								</tr>
								<tr class="seasonList" style="display:<?=$display?>">
									<td align="center">준성수기*평일 할증</td>			
									<td>&nbsp;</td>
									<td style="text-align:center"><input type="text" name="semiBusySeason[]" style="width:30px" class="input" value="<?=$roption['semiBusySeason']?>" <?=$optTb1_disabled?> />%</td>
								</tr>
								<tr class="seasonList" style="display:<?=$display?>">
									<td align="center">준성수기*주말공휴일 할증</td>			
									<td>&nbsp;</td>
									<td style="text-align:center"><input type="text" name="semiBusyHolidaySeason[]" style="width:30px" class="input" value="<?=$roption['semiBusyHolidaySeason']?>" <?=$optTb1_disabled?> />%</td>
								</tr>
								<tr class="seasonList" style="display:<?=$display?>">
									<td align="center">비수기*주말공휴일 할증</td>			
									<td>&nbsp;</td>
									<td style="text-align:center"><input type="text" name="holidaySeason[]" style="width:30px" class="input" value="<?=$roption['holidaySeason']?>" <?=$optTb1_disabled?> />%</td>
								</tr>
							<?	}
							}?>
							</tbody>
						</table>					
						
						<table border="0" cellpadding="0" cellspacing="0" id="rentOptTable2" style="display:<?=$optTb2_display?>">
							<caption style="padding:0px;"><input type="button" value="항목 추가" onclick="javascript:rentOptInsert2()" style="width:100%" /></caption>							
							<thead>
								<tr>
									<th class="firstTh">약정기간</th>
									<th>분납/일시불</th>
									<th>가격</th>
									<th class="optMoney">보증금</th>
									<th>선납금</th>
									<th>재고</th>
									<th class="optMulti">비고</th>
								</tr>	
							</thead>
							<tbody>
							<?
							$roptions = rentProduct::getoptions($_data->pridx);
							if(_array($roptions)){
								$i=0;
								foreach($roptions as $roidx=>$roption){
									if($i==0) $cprice = $roption['custPrice'];
							?>
								<tr class="itemSepRow">
									<td class="firstTd" align="center"><input type="text" name="optionName[]" style="width:40%;" class="input autoSolv2" value="<?=$roption['optionName']?>" <?=$optTb2_disabled?> />개월</td>
									<td align="center">
										<select name="optionPay[]" class="optpay" onchange="javascript:optPay(this.options[this.selectedIndex].value)" <?=$optTb2_disabled?>>
											<option value="일시납" <?=($roption['optionPay']=="일시납")? "selected":"";?>>일시납</option>
											<option value="분납" <?=($roption['optionPay']=="분납")? "selected":"";?>>분납</option>
										</select>
									</td>
									<td>
										<input type="text" name="nomalPrice[]" value="<?=$roption['nomalPrice']?>" style="width:80px" class="input autoSolv2" <?=$optTb2_disabled?> />원<br>
										<input type="hidden" name="roptidx[]" value="<?=$roidx?>" <?=$optTb2_disabled?> />
										<span id="instDiv" style="display:<?=($roption['optionPay']=="분납")? "": "none";?>">
										(<input type="text" name="installmentPay[]" value="<?=($roption['nomalPrice']/$roption['optionName'])?>" style="width:50px" class="input autoSolv3" />*<span id="installmentMonth"><?=$roption['optionName']?></span>개월)</span>
									</td>
									<td class="optMoney"><input type="text" name="deposit[]" style="width:80px;" value="<?=$roption['deposit']?>" class="input" />원</td>
									<td><input type="text" name="prepay[]" style="width:80px;" value="<?=$roption['prepay']?>" class="input" />원</td>
									<td align="center"><input type="text" name="productCount[]" value="<?=$roption['productCount']?>" style="width:60px;text-align:center;" class="input" <?=$optTb2_disabled?> />개</td>
									<td align="center" class="optMulti">
										<input type="button" value="삭제" onclick="javascript:delOptitem2(this)" />
									</td>
								</tr>
								<?	
										$i++;
									}
								}
								?>
							</tbody>
						</table>

						<textarea name="optformatcode" style="display:none">
							<tr class="itemSepRow">
								<td class="firstTd optMulti" align="center" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="optionName[]" style="width:90%;" class="input" /></td>
								<td align="center"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<select name="optionGrade[]">
										<?
										foreach (rentProduct::_status() as $k=>$v) {
											echo "<option value='".$k."'>".$v."</option>";
										}
										?>
									</select>
								</td>				
								<td align="center">일반가(24시간)</td>
								<td><input type="text" name="custPrice[]" value="0" style="width:80px" class="input autoSolv" />원 - <input type="text" name="priceDiscP[]" value="0" style="width:30px" class="input autoSolv" />%</td>
								<td><input type="text" name="nomalPrice[]" style="width:80px;" value="0" class="input autoPerSolv" />원
									<input type="hidden" name="roptidx[]" value="" /></td>
								<td align="center" class="optTime itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<input type="text" name="productTimeover_percent[]" value="" style="width:30px;" class="input autoSolv_time">% 
									<input type="text" name="productTimeover_price[]" value="" style="width:70px;" class="input autoPerSolv">원
								</td>
								<td align="center" class="optDay itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<input type="text" name="productHalfday_percent[]" value="" style="width:30px;" class="input autoSolv_half">% 
									<input type="text" name="productHalfday_price[]" value="" style="width:70px;" class="input autoPerSolv">원
								</td>
								<td align="center" class="optDay2 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<input type="text" name="productOverHalfTime_percent[]" value="" style="width:30px;" class="input autoSolv_halftime">% 
									<input type="text" name="productOverHalfTime_price[]" value="" style="width:70px;" class="input autoPerSolv">원
								</td>
								<td align="center" class="optDay3 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
									<input type="text" name="productOverOneTime_percent[]" value="" style="width:30px;" class="input autoSolv_onetime">% 
									<input type="text" name="productOverOneTime_price[]" value="" style="width:70px;" class="input autoPerSolv">원
								</td>
								<td align="center"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="productCount[]" value="0" style="width:60px;text-align:center;" class="input" />개</td>
								<td align="center" class="optMulti" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
								<input type="button" value="삭제" onclick="javascript:delOptitem(this)" />
								<!-- <a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a> ---></td>
							</tr>								
							<?// if($categoryRentInfo['useseason'] == '1'){ ?>
							<tr class="seasonList" style="display:<?=$display?>">
								<td align="center">성수기*평일 할증</td>			
								<td colspan="2"><input type="text" name="busySeason[]" style="width:30px" class="input" />%</td>
							</tr>
							<tr class="seasonList" style="display:<?=$display?>">
								<td align="center">성수기*주말공휴일 할증</td>			
								<td colspan="2"><input type="text" name="busyHolidaySeason[]" style="width:30px" class="input" />%</td>
							</tr>
							<tr class="seasonList" style="display:<?=$display?>">
								<td align="center">준성수기*평일 할증</td>			
								<td colspan="2"><input type="text" name="semiBusySeason[]" style="width:30px" class="input" />%</td>
							</tr>
							<tr class="seasonList" style="display:<?=$display?>">
								<td align="center">준성수기*주말공휴일 할증</td>			
								<td colspan="2"><input type="text" name="semiBusyHolidaySeason[]" style="width:30px" class="input" />%</td>
							</tr>
							<tr class="seasonList" style="display:<?=$display?>">
								<td align="center">비수기*주말공휴일 할증</td>			
								<td colspan="2"><input type="text" name="holidaySeason[]" style="width:30px" class="input" />%</td>
							</tr>
							<?// } ?>
						</textarea>
						<textarea name="optformatcode2" style="display:none">
							<tr class="itemSepRow">
								<td class="firstTd" align="center"><input type="text" name="optionName[]" style="width:40%;" class="input" />개월</td>
								<td align="center">
									<select name="optionPay[]" class="optpay" onchange="javascript:optPay(this.options[this.selectedIndex].value)">
										<option value="일시납">일시납</option>
										<option value="분납">분납</option>
									</select>
								</td>
								<td>
									<input type="text" name="nomalPrice[]" value="0" style="width:80px" class="input autoSolv2" />원<br>
									<input type="hidden" name="roptidx[]" value="" />
									<span id="instDiv" style="display:none">
									(<input type="text" name="installmentPay[]" value="0" style="width:50px" class="input autoSolv3" />*<span id="installmentMonth"></span>개월)</span>
								</td>
								<td class="optMoney"><input type="text" name="deposit[]" style="width:80px;" value="0" class="input" />원</td>
								<td><input type="text" name="prepay[]" style="width:80px;" value="0" class="input" />원</td>
								<td align="center"><input type="text" name="productCount[]" value="0" style="width:60px;text-align:center;" class="input" />개</td>
								<td align="center" class="optMulti">
									<input type="button" value="삭제" onclick="javascript:delOptitem2(this)" />
								</td>
							</tr>
						</textarea>
						</div>
						
						
						<span style="position:absloute; z-index:2; display:none">
							<span class="font_orange">* 정상가 <strike>5,000</strike>로 표기됨, 0 입력시 표기안됨.&nbsp;</span> <br>
							<span class="font_orange">* 렌탈상품의 경우 상품옵션관리의 가격으로 측정됩니다.&nbsp;</span>
						</span>
					</td>
				</tr>

				<? 
				$longrentinfo = venderLongrentCharge(0,$_data->pridx);		
				if(count($longrentinfo)==0){
					$longrentinfo2 = venderLongrentCharge(0,0);
					
					if(count($longrentinfo2)==0){
						$longrentinfo = rentLongrentCharge(pick($code,$parentcode));
					}else{
						$longrentinfo = $longrentinfo2;
					}
				}
				?>
				<tr class="rentalItemArea7" style="display:<?=($categoryRentInfo['pricetype']=='period'||$categoryRentInfo['pricetype']=='long')?"":"none";?>">
					<th>장기대여 설정</th>
					<td colspan="3">
						<style type="text/css">
						#longrentDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
						#longrentDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j(document).on('click','#longrentDiv>div>img',function(e){
								rmvLongrentCharge(this);
							});

							<?if(_array($longrentinfo)){?>
								$j('#addLongrent_sday').val($j('input[name=last_eday]').val());
							<?}?>

							$j('#longRentTbl').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								var del = $j(ptr).find('input[name^=addLongrentPercent]');
								var cel = $j(ptr2).find('input[name^=nomalPrice]');
								var sel = $j(ptr).find('input[name^=addLongrentPrice]');
								var d = $j('#addLongrent_sday').val();

								var customerp = parseInt($j(cel).val()*d);
								var discountp = parseInt($j(del).val());
								var sellp = 0;
								if(!isNaN(customerp) && customerp >= 0){
									sellp = customerp;
									if(!isNaN(discountp) && discountp > 0){
										$j(del).val(discountp);
										sellp = parseInt(customerp + Math.floor(customerp*discountp/100));
									}else{
										$j(del).val('0');
									}
								}
								$j(sel).val(sellp);
							});
							$j('#longRentTbl').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=addLongrentPercent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=addLongrentPrice]');
								var d = $j('#addLongrent_sday').val();

								var customerp = parseInt($j(cel).val()*d);
								var sellprice = parseInt($j(sel).val());
								var discper=0;
								if(!isNaN(customerp) && customerp >= 0){
									discper = $j(del).val();
									if(!isNaN(sellprice) && sellprice > 0){
										$j(sel).val(sellprice);
										discper = parseInt(Math.round(100*sellprice/customerp)-100);
									}else{
										$j(sel).val('0');
									}
								}
								$j(del).val(discper);
							});
						});
						function rmvLongrentCharge(el){
							$j(el).parent().remove();
							if($j('#addLongrent_sday').val()>$j(el).parent().find('input[name^=longrent_sday]').val()){
								$j('#addLongrent_sday').val($j(el).parent().find('input[name^=longrent_sday]').val());
							}
						}
						function addLongrentCharge(){
							var sd = parseInt($j('#addLongrent_sday').val());
							var ed = parseInt($j('#addLongrent_eday').val());
							var p = parseInt($j('#addLongrentPercent').val());
							var addprice = $j('#addLongrentPrice').val();
							if(isNaN(sd) || sd < 1){
								alert('날짜를 올바르게 입력하세요.');
								$j('#addLongrent_sday').focus();
							}else if(isNaN(ed) || ed < 1){
								alert('날짜를 올바르게 입력하세요.');
								$j('#addLongrent_eday').focus();
							}else if(isNaN(p) || p < 1){
								alert('추가과금율를 올바르게 입력하세요.');
								$j('#addLongrentPercent').focus();
							}else{
								var dupvalel = null;
								$j('#longrentDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=longrent_sday]').val() == String(sd)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="longrent_sday[]" value="'+sd+'"><input type="hidden" name="longrent_eday[]" value="'+ed+'"><input type="hidden" name="longrent_percent[]" value="'+p+'"><span style="float:left">'+sd+'~'+ed+' 일까지 '+p+'% 추가 ('+number_format(addprice)+'원)</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#longrentDiv').append(html);
									$j('#addLongrent_sday').val(ed+1);
									$j('#addLongrent_eday').val('');
									$j('#addLongrentPercent').val('');
									$j('#addLongrentPrice').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff" id="longRentTbl">
							<tr>
								<th style="width:100px;">기간</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addLongrent_sday" id="addLongrent_sday" value="" style="width:30px;" />~
									<input type="text" name="addLongrent_eday" id="addLongrent_eday" value="" style="width:30px;" />
									일까지
								</td>
								<th style="width:100px;">추가과금</th>
								<td style="padding:5px;">
									<input type="text" name="addLongrentPercent" id="addLongrentPercent" class="autoSolv" value="" style="width:30px;" />% 
									<input type="text" name="addLongrentPrice" id="addLongrentPrice" class="autoPerSolv" value="" style="width:60px;" />원
								</td>
								<td>
									<input type="button" name="addLongrentBtn" value="추가" onclick="javascript:addLongrentCharge()" />
								</td>
							</tr>
						</table>
						<div style="padding:3px 0px; clear:both" id="longrentDiv">
							<? 
							if(_array($longrentinfo)){
								foreach($longrentinfo as $k=>$v){ 
									
									$dayPrice = $cprice*$v['sday'];
									$disPrice = number_format($dayPrice + ($dayPrice * $v['percent']/100));
							?>
							<div>
								<input type="hidden" name="longrent_sday[]" value="<?=$v['sday']?>">
								<input type="hidden" name="longrent_eday[]" value="<?=$v['eday']?>">
								<input type="hidden" name="longrent_percent[]" value="<?=$v['percent']?>">
								<span style="float:left">
								<?=$v['sday']."~".$v['eday']?>일까지
								<?=$v['percent']?>% 추가 (<?=$disPrice?>원)
								</span>
								<img src="../admin/images/btn_del.gif" alt="삭제" align="right" />
							</div>
							<?	
							}
							}?>
						<input type="hidden" name="last_eday" value="<?=$v['eday']+1?>">
						</div>
						
					</td>
				</tr>		
									
				<tr class="rentalItemArea rentalItemArea5" style="display:<?=($categoryRentInfo['pricetype'] != 'period')?"":"none";?>">
					<th> 장기할인 설정</th>
					<td colspan="3" style="padding:7px 7px">
						※ 장기할인서비스를 이용하지 않을려면 기간과 할인율에 0을 입력하시면 됩니다.<br>
						<style type="text/css">
						#rangeDiscountDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
						#rangeDiscountDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						function number_format(num){
							var num_str = num.toString();
							var result = "";

							for(var i=0; i<num_str.length; i++){
								var tmp = num_str.length - (i+1);
								if(((i%3)==0) && (i!=0))    result = ',' + result;
								result = num_str.charAt(tmp) + result;
							}
							return result;
						}

						$j(function(){
							$j(document).on('click','#rangeDiscountDiv>div>img',function(e){
								rmvRangDiscount(this);
							});

							$j('#longDiscountTbl').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=addRangeDiscountPercent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=addRangeDiscountPrice]');
								var d = $j('#addRangeDiscountDay').val();

								var customerp = parseInt($j(cel).val()*d);
								var discountp = parseInt($j(del).val());
								var sellp = 0;
								if(!isNaN(customerp) && customerp >= 0){
									sellp = customerp;
									if(!isNaN(discountp) && discountp > 0 && discountp <100){
										$j(del).val(discountp);
										sellp = parseInt(customerp - Math.floor(customerp*discountp/100));
									}else{
										$j(del).val('0');
									}
								}
								$j(sel).val(sellp);
							});
							$j('#longDiscountTbl').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=addRangeDiscountPercent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=addRangeDiscountPrice]');
								var d = $j('#addRangeDiscountDay').val();

								var customerp = parseInt($j(cel).val()*d);
								var sellprice = parseInt($j(sel).val());
								var discper=0;
								if(!isNaN(customerp) && customerp >= 0){
									discper = $j(del).val();
									if(!isNaN(sellprice) && sellprice > 0){
										$j(sel).val(sellprice);
										discper = parseInt(Math.round(100*sellprice/customerp));
									}else{
										$j(sel).val('0');
									}
								}
								$j(del).val(discper);
							});
						});
						function rmvRangDiscount(el){
							$j(el).parent().remove();
						}
						function addRangeDiscount(){
							var d = $j('#addRangeDiscountDay').val();//parseInt($j('#addRangeDiscountDay').val());
							var p = parseInt($j('#addRangeDiscountPercent').val());	
							var cel = parseInt($j('input[name^=custPrice]').val());

							if(isNaN(d) || d < 0){
								alert('기간을 올바르게 입력하세요.');
								$j('#addRangeDiscountDay').focus();
							}else if(isNaN(p) || p < 0|| p>100){
								alert('할인율을 올바르게 입력하세요.');
								$j('#addRangeDiscountPercent').focus();
							}else{
								var dupvalel = null;
								$j('#rangeDiscountDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=discrangeday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else if(d<2){
									alert('장기할인은 2일 이상가능합니다.');
								}else{
									var dayPrice = parseInt(cel*d);
									var disPrice = number_format(dayPrice - parseInt(dayPrice*p/100));

									html = '<div><input type="hidden" name="discrangeday[]" value="'+d+'"><input type="hidden" name="discrangepercent[]" value="'+p+'"><span style="float:left">'+d+' 일이상 '+p+'% 할인 ('+disPrice+'원)</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#rangeDiscountDiv').append(html);
									$j('#addRangeDiscountDay').val('');
									$j('#addRangeDiscountPercent').val('');
									$j('#addRangeDiscountPrice').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff" id="longDiscountTbl">
							<colgroup>
								<col width="100" />
								<col width="" />
								<col width="100" />
								<col width="" />
								<col width="" />
							</colgroup>
							<tr>
								<th style="background:#f9f9f9">기간</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addRangeDiscountDay" id="addRangeDiscountDay" value="" style="width:50px;" />
									일 이상</td>
								<th style="background:#f9f9f9">할인율</th>
								<td style="padding:5px;">
									<input type="text" name="addRangeDiscountPercent" id="addRangeDiscountPercent" class="autoSolv" value="" style="width:50px;" />% 
									<input type="text" name="addRangeDiscountPrice" id="addRangeDiscountPrice" class="autoPerSolv" value="" style="width:60px;" />원
									<span id="disprice"></span>
								</td>
								<td><input type="button" name="addRangeDiscountBtn" value="추가" onclick="javascript:addRangeDiscount()" /></td>
							</tr>
						</table>
						<? 
						$ldiscinfo = venderLongDiscount(0,$_data->pridx);
						if(count($ldiscinfo)==0){
							$ldiscinfo2 = venderLongDiscount(0,0);

							if(count($ldiscinfo2)==0){
								$ldiscinfo = rentLongDiscount(pick($code,$parentcode));
							}else{
								$ldiscinfo = $ldiscinfo2;
							}
						}
						?>
						<div style="width:100%;padding:3px 0px; clear:both" id="rangeDiscountDiv">
							<?
							if(_array($ldiscinfo)){
								foreach($ldiscinfo as $dday=>$dpercent){
									$dayPrice = $cprice*$dday;
									$disPrice = number_format($dayPrice - ($dayPrice * $dpercent/100));
							?>
							<div>
								<input type="hidden" name="discrangeday[]" value="<?=$dday?>">
								<input type="hidden" name="discrangepercent[]" value="<?=$dpercent?>">
								<span style="float:left;line-height:22px">
									<?=$dday?> 일 이상
									<?=$dpercent?> % 할인 (<?=$disPrice?>원)
								</span>
								<img src="../admin/images/btn_del.gif" alt="삭제" align="right" style="float:right" />
							</div>
							<?
								}
							}
							?>
						</div>
					</td>
				</tr>
				
				<tr class="rentalItemArea">
					<th> 환불 설정</th>
					<td colspan="3" style="padding:7px 7px">
						※ 환불설정을 이용하지 않을려면 취소일과 수수료에 0을 입력하시면 됩니다.<br>
						<style type="text/css">
						#refundDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
						#refundDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j(document).on('click','#refundDiv>div>img',function(e){
								rmvRefundCommi(this);

							});
						});
						function rmvRefundCommi(el){
							$j(el).parent().remove();
						}
						function addRefundCommi(){
							var d = parseInt($j('#addRefundDay').val());
							var p = parseInt($j('#addRefundPercent').val());			
							if(isNaN(d) || d < 0){
								alert('취소일을 올바르게 입력하세요.');
								$j('#addRefundDay').focus();
							}else if(isNaN(p) || p < 0|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">'+d+' 일전 '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').append(html);
									$j('#addRefundDay').val('');
									$j('#addRefundPercent').val('');
								}
							}
							
						}

						function addRefundCommi2(){
							var d = parseInt($j('#addRefundDay2').val());
							var p = parseInt($j('#addRefundPercent2').val());
							if(isNaN(p) || p < 1|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent2').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 전) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay2').val('-1');
									$j('#addRefundPercent2').val('');
								}
							}
							
						}

						function addRefundCommi3(){
							var d = parseInt($j('#addRefundDay3').val());
							var p = parseInt($j('#addRefundPercent3').val());
							if(isNaN(p) || p < 1|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent3').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 후) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay3').val('0');
									$j('#addRefundPercent3').val('');
								}
							}
							
						}
						</script>
						<table width="600" cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
							<colgroup>
								<col width="20%" />
								<col width="27%" />
								<col width="20%" />
								<col width="27%" />
								<col width="" />
							</colgroup>
							<tr>
								<th style="background:#f9f9f9">취소일</th>
								<td class="norbl" style="padding:5px;"><input type="text" name="addRefundDay" id="addRefundDay" value="" style="width:50px;" /> 일전</td>
								<th style="background:#f9f9f9">수수료</th>
								<td style="padding:5px;"><input type="text" name="addRefundPercent" id="addRefundPercent" value="" style="width:50px;" /> %</td>
								<td align="center"><input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi()" /></td>
							</tr>
							<tr>
								<th colspan="2" style="background:#f9f9f9">당일환불(배송 전)</th>
								<th style="background:#f9f9f9">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay2" id="addRefundDay2" value="-1" />
									<input type="text" name="addRefundPercent2" id="addRefundPercent2" value="" style="width:50px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi2()" />
								</td>
							</tr>
							<tr>
								<th colspan="2" style="background:#f9f9f9">당일환불(배송 후)</th>
								<th style="background:#f9f9f9">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay3" id="addRefundDay3" value="0" />
									<input type="text" name="addRefundPercent3" id="addRefundPercent3" value="" style="width:50px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi3()" />
								</td>
							</tr>
						</table>
						<div style="width:100%; padding:3px 0px; clear:both" id="refundDiv">
							<? 
							$refundinfo = venderRefundCommission(0,$_data->pridx);
						
							if(count($refundinfo)==0){
								$refundinfo2 = venderRefundCommission(0,0);
								
								if(count($refundinfo2)==0){
									$refundinfo = rentRefundCommission(pick($code,$parentcode));
								}else{
									$refundinfo = $refundinfo2;
								}
							}
							?>
						
							<? if(_array($refundinfo)){
							foreach($refundinfo as $rday=>$rpercent){ ?>
							<div>
								<input type="hidden" name="refundday[]" value="<?=$rday?>">
								<input type="hidden" name="refundpercent[]" value="<?=$rpercent?>">
								<span style="float:left">
								<?
								if($rday==-1){
									echo "당일환불(배송전)";
								}else if($rday==0){
									echo "당일환불(배송후)";
								}else{
									echo $rday."일전";
								}
								?>
								<?=$rpercent?>
								%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						</div>
					</td>
				</tr>
				<tr class="rentalItemArea10">
					<th>예약 확정 방식</th>
					<td colspan="3">
						<input type=radio name=booking_confirm value="now" <?if($_data->booking_confirm=="now")echo"checked";?>>결제와 동시  
						<input type=radio name=booking_confirm value="select" <?if($_data->booking_confirm!="now")echo"checked";?>>
						<select name="booking_confirm_time">
							<option value="">선택</option>
							<option value="00:10" <?if($_data->booking_confirm=="00:10")echo"selected";?>>10분</option>
							<option value="00:20" <?if($_data->booking_confirm=="00:20")echo"selected";?>>20분</option>
							<option value="00:30" <?if($_data->booking_confirm=="00:30")echo"selected";?>>30분</option>
							<? for($i=1;$i<=24;$i++){?>
							<option value="<?=sprintf('%02d',$i)?>:00" <?if($_data->booking_confirm==sprintf('%02d',$i).":00")echo"selected";?>><?=$i?>시간</option>
							<? } ?>
						</select>
						이내 확인 알림
					</td>
				</tr>


				<tr>
					<th>회원등급별적립</th>					
					<td colspan="3">
						<?
						$groupdiscount = getGroupReserves(pick($_data->productcode,$code));					
						$rgroupdiscount = getReqGroupReserves($_data->productcode);
						
						foreach($groupdiscount as $gdiscount){ ?>
						<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;<?=($gdiscount['reserve'] <1)?($gdiscount['reserve']*100).'%':$gdiscount['reserve']?></span>
<?						}
						?>
						<script language="javascript" type="text/javascript">
							function changeDiscount(){
								var pwin = window.open("/admin/product_ext/pop_greserve.php?code=<?=$_data->productcode?>","ReqWin","height=450,width=380,scrollbars=yes");
							}
						</script>
						<input type="button" value="변경하기" onclick="javascript:changeDiscount()"  />
						<? if(_array($rgroupdiscount)){ ?>
						<br><span style="color:red">[변경요청] </span>
						<?		foreach($rgroupdiscount as $gdiscount){ ?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;<?=($gdiscount['reserve'] <1)?($gdiscount['reserve']*100).'%':$gdiscount['reserve']?></span>
	<?							}	?>
							<script language="javascript" type="text/javascript">
							function confirmChange(){
								$j.post('/admin/product_ext/ajax.php',{'act':'confirmreservechange','code':'<?=$_data->productcode?>'}
								,function(data){
									if(data.err != 'ok'){
										alert(data.err);
									}else{
										document.location.reload();
									}
								},'json');
							}
							
							function rejectChange(){
								$j.post('/admin/product_ext/ajax.php',{'act':'rejectreservechange','code':'<?=$_data->productcode?>'}
								,function(data){
									if(data.err != 'ok'){
										alert(data.err);
									}else{
										document.location.reload();
									}
								},'json');
							}
							</script>
							<input type="button" value="승인" onclick="javascript:confirmChange();" style="margin-right:5px;" /><input type="button" value="거부" onclick="javascript:rejectChange();" style="margin-right:5px;" />
						<?	} ?>
						
					</td>
				</tr>
				<tr>
					<th>추천인등급별 적립</th>					
					<td colspan="3">
						<?
						$groupdiscount2 = getGroupReseller_Reserves(pick($_data->productcode,$code));					
						$rgroupdiscount2 = getReqGroupReseller_Reserves($_data->productcode);
						
						foreach($groupdiscount2 as $gdiscount2){ ?>
						<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount2['group_name']?></span>&nbsp;<?=($gdiscount2['reserve'] <1)?($gdiscount2['reserve']*100).'%':$gdiscount2['reserve']?></span>
<?						}
						?>
						<script language="javascript" type="text/javascript">
							function changeDiscount2(){
								var pwin = window.open("/admin/product_ext/pop_greseller_reserve.php?code=<?=$_data->productcode?>","ReqWin","height=450,width=380,scrollbars=yes");
							}
						</script>
						<input type="button" value="변경하기" onclick="javascript:changeDiscount2()"  />
						<? if(_array($rgroupdiscount2)){ ?>
						<br><span style="color:red">[변경요청] </span>
						<?		foreach($rgroupdiscount2 as $gdiscount2){ ?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount2['group_name']?></span>&nbsp;<?=($gdiscount2['reserve'] <1)?($gdiscount2['reserve']*100).'%':$gdiscount2['reserve']?></span>
	<?							}	?>
							<script language="javascript" type="text/javascript">
							function confirmChange2(){
								$j.post('/admin/product_ext/ajax.php',{'act':'confirmRereservechange','code':'<?=$_data->productcode?>'}
								,function(data){
									if(data.err != 'ok'){
										alert(data.err);
									}else{
										document.location.reload();
									}
								},'json');
							}
							
							function rejectChange2(){
								$j.post('/admin/product_ext/ajax.php',{'act':'rejectRereservechange','code':'<?=$_data->productcode?>'}
								,function(data){
									if(data.err != 'ok'){
										alert(data.err);
									}else{
										document.location.reload();
									}
								},'json');
							}
							</script>
							<input type="button" value="승인" onclick="javascript:confirmChange2();" style="margin-right:5px;" /><input type="button" value="거부" onclick="javascript:rejectChange2();" style="margin-right:5px;" />
						<?	} ?>
						
					</td>
				</tr>

					<!--
					<th>적립금(률)</th>
					<td>
						<input name=reserve value="<?=$_data->reserve?>" size=5 maxlength=6 class="input" style="width:50px" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);">
						<select name="reservetype" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
							<option value="N"<?=($_data->reservetype=='N'?" selected":"")?>>적립금(￦)</option>
							<option value="Y"<?=($_data->reservetype!="N"?"":" selected")?>>적립률(%)</option>
						</select>
						<br>
						<span class="font_orange" style="font-size:8pt;letter-spacing:-0.5pt">* 적립률은 소수점 둘째자리까지 입력 가능합니다.<br>
						* 적립률에 대한 적립 금액 소수점 자리는 반올림.</span></td>
					-->
				</tr>
				<? /* 과세 구분 추가 jdy ?>
				<tr>
					<th class="reqFld">과세여부</th>
					<td colspan="3">
						<input type=radio id="tax_yn1" name="tax_yn" value="0" <? if ($_data) { if ($_data->tax_yn=="" || $_data->tax_yn=="0") echo "checked"; } else echo "checked";  ?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=tax_yn1>일반과세</label>
						&nbsp;
						<input type=radio id="tax_yn2" name="tax_yn" value="1" <? if ($_data) { if ($_data->tax_yn=="1") echo "checked"; } ?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=tax_yn2>비과세</label>
					</td>
				</tr>
				<? /* 과세 구분 추가 jdy ?>
				<TR class="productItemArea">
					<th class="reqFld">도매가격</th>
					<td colspan="3">
						<input name="productdisprice" value="<?=ereg_replace("\"","&quot",$_data->productdisprice)?>" size=20 maxlength=50 onKeyDown="chkFieldMaxLen(50)" class="input" style="width:20%">
					</td>
				</tr>
				<? */ ?>
				<? /****** 수수료 관련 수정 jdy ************/?>
				<? if ($_data->productcode){ //수정일 경우. 업체의 수수료 정보를 조회
				$vender_more = getVenderMoreInfo($_data->vender);
				$commission_type = $vender_more['commission_type'];

				if ($account_rule=="1" || $commission_type=="1") {
				//공급가로 운영되거나.. 수수료지만 개별 수수료일?만 나타남.
				?>
				<tr class="productItemArea">
					<th class="reqFld">
						<?= $adjust_title ?>
					</th>
					<td colspan="3">
						<? if ($account_rule=="1") {
	
						$cf_num = $_data->cf_cost;
						$rq_num = $_data->rq_cost;
						if ($cf_num=="") $cf_num="0";
	
						if ($_data->first_approval=="1") {
	
							$adjust = $_data->sellprice - $cf_num;
	
							$com_status = "<b>현재 공급가 <span class=\"font_blue\">".$cf_num."</span>원 (수수료 ".$adjust."원)</b>";
						}else{
							$com_status = "<b>승인대기</b>";
						}
	
						if ($_data->status == '') {
							$com_status .= "";
						}else if ($_data->status == '1') {
							$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>공급가 요청중 <span class=\"font_blue\">".$rq_num."</span>원</b>";
						}else if ($_data->status == '3') {
							$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>공급가 요청거부 <span class=\"font_blue\">".$rq_num."</span>원</b>";
						}
	
						$com_title = "공급가";
						$com_input = "<input type=text name=up_rq_cost value\"\" size=10 maxlength=10 onkeyup=\"strnumkeyup(this)\" class=input>원";
						
						echo $com_status ?>
						<br/>
						<span class="font_orange">* 수수료 = 판매가 - 승인상품공급가</span>
						<? }else{
						$cf_num = $_data->cf_com;
						$rq_num = $_data->rq_com;
						if ($cf_num=="") $cf_num="0";
	
						if ($_data->first_approval=="1") {
							$com_status = "<b>현재 수수료 <span class=\"font_blue\">".$cf_num."</span>%</b>";
						}else{
							$com_status = "<b>승인대기</b>";
						}
	
						if ($_data->status == '') {
							$com_status .= "";
						}else if ($_data->status == '1') {
							$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>수수료 요청중 <span class=\"font_blue\">".$rq_num."</span>%</b>";
						}else if ($_data->status == '3') {
							$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>수수료 요청거부 <span class=\"font_blue\">".$rq_num."</span>%</b>";
						}
	
						$com_title = "수수료";
						$com_input = "<input type=text name=up_rq_com value=\"\" size=3 maxlength=3 onkeyup=\"strnumkeyup(this)\" class=input>%";		
						
						echo $com_status;
					} ?>
						<button style="color:#ffffff;background-color:#000000;border:0;width:50px;height:25px;cursor:pointer; margin-left:15px;" onclick="commissionDivView();">변경</button>
						<? if (!$_data->status) { ?>
						&nbsp;&nbsp;<span style="color:red;font-weight:bold">*
						<?= $adjust_title ?>
						가 지정되지 않았습니다.
						<?= $adjust_title ?>
						를 지정해주세요.</span>
						<? } ?>
						<br/>
						<div id="commission_div" style="position:absolute;width:450px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;display:none;margin-top:10px;">
							<div style="width:100%;text-align:right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionDivView('N');" >X</span></div>
							<div style="width:100%;margin-top:5px;">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
									<col width=100 />
									<col width= />
									<col width=100 />
									<tr>
										<td height=2 colspan="3" bgcolor=#808080></td>
									</tr>
									<? if ($_data->status == '1') {?>
									<tr>
										<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>요청
											<?= $com_title ?>
										</td>
										<td style=padding:7,10>
											<?= $rq_num ?>
											<? if ($account_rule=="1") { ?>
											원
											<? }else {?>
											%
											<? }?>
											<input type="hidden" name="commission_result" />
										</td>
										<td align="right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionOk('Y')">승인</span>&nbsp; <span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionOk('N')">거부</span></td>
									</tr>
									<tr>
										<td height=1 colspan=3 bgcolor=E7E7E7></td>
									</tr>
									<? } ?>
									<tr>
										<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>
											<?= $com_title ?>
										</td>
										<td style=padding:7,10>
											<?= $com_input ?>
										</td>
										<td align="right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionChange()">변경</span></td>
									</tr>
									<tr>
										<td height=1 colspan=3 bgcolor=E7E7E7></td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<?				}
		}else { 	//입력일 경우 그냥 입력폼 노출
		?>
				<tr class="productItemArea">
					<th class="reqFld">
						<?= $adjust_title ?>
					</th>
					<td colspan="3">
						<? if ($account_rule=="1") { ?>
						<input type="text" size="10" class="input" name="up_rq_cost" id="up_rq_cost"/>
						원 (상품 공급가를 입력해주세요.) <br/>
						<span class="font_orange">* 수수료 = 판매가 - 승인상품공급가</span>
						<? }else{ ?>
						<input type="text" size="10" class="input" name="up_rq_com" id="up_rq_com"/>
						% <br/>
						<span class="font_orange">* 전체상품 동일 수수료인 경우 입력해도 적용되지 않습니다.</span>
						<? } ?>
					</td>
				</tr>
				<?
		} /****** 수수료 관련 수정 jdy ************/?>
				<? if ($gongtype=="N") { ?>
				<tr>
					<th>판매가격 대체문구</th>
					<td colspan="3">
						<input type=checkbox id="idx_dicker1" name=dicker value="Y" <? if ($_data) { if ($dicker=="Y") echo "checked";}?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dicker1><b>사용함</b></label>
						&nbsp;
						<input type=text name=dicker_text value="<?=$dicker_text?>" size=20 maxlength=20 onKeyDown="chkFieldMaxLen(20)" class="input">
						<span class="font_orange">* 예) 판매대기상품, 상담문의(000-000-000)</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>판매가격 대체문구</b> 입력가능 글자 수는 한글 10자, 영문 20자로 제한되어 있습니다.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>판매가격 대체문구</b> 사용시 주문은 진행되지 않습니다.
						<? } ?>
					</td>
				</tr>				
				<tr>
					<th>구입원가</th>
					<td  colspan="3">
						<input name=buyprice value="<?=$_data->buyprice?>" size=16 maxlength=10 class="input" style="width:100%">
					</td>
				</tr>
				<tr>
					<th>제조회사</th>
					<td>
						<input name=production value="<?=$_data->production?>" size=23 maxlength=20 onKeyDown="chkFieldMaxLen(50)" class="input">
						<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
					<th>원산지</th>
					<td>
						<input name=madein value="<?=$_data->madein?>" size=23 maxlength=20 onKeyDown="chkFieldMaxLen(30)" class="input">
						<a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
				</tr>
				<tr>
					<th>브랜드</th>
					<td>
						<input type=text name=brandname value="<?=$_data->brandname?>" size=23 maxlength=50 onKeyDown="chkFieldMaxLen(50)" class="input">
						<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a><br>
						<span class="font_orange">* 브랜드를 직접 입력시에도 등록됩니다.</span></td>
					<th>모델명</th>
					<td>
						<input name=model value="<?=$_data->model?>" size=23 maxlength=40 onKeyDown="chkFieldMaxLen(50)" class="input">
						<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
				</tr>
				<tr>
					<th>진열코드</th>
					<td>
						<input name=selfcode value="<?=$_data->selfcode?>" size=35 maxlength=20 onKeyDown="chkFieldMaxLen(20)" class="input" style="width:90%">
						<br />
						<span class="font_orange">* 쇼핑몰에서 자동으로 발급되는 상품코드와는 별개로 운영상 필요한 자체상품코드를 입력해 주세요.<br>
						* 진열코드 관련 설정은 <a href="javascript:parent.parent.topframe.GoMenu(1,'shop_productshow.php');"><span class="font_blue">상점관리 > 쇼핑몰 환경 설정 > 상품 진열 기타 설정</a>변경할 수 있습니다. </span></td>
					<th>출시일</th>
					<td>
						<input name=opendate value="<?=$_data->opendate?>" size=20 maxlength=8 class="input">
						&nbsp;&nbsp;예)
						<?=DATE("Ymd")?>
						(출시년월일)<br>
						<span class="font_orange">* 가격비교 페이지 등 제휴업체 관련 노출시 사용됩니다.<br>
						* 잘못된 출시일 지정으로 인한 문제는 상점에서 책임지셔야 됩니다.</span></td>
				</tr>
				<tr class="productItemArea">
					<th class="reqFld">수량</th>
					<td colspan="3">
						<?
		if ($gongtype=="Y") {
			
			if ($_data) {
				$quantity=$_data->quantity;
				if($_data->quantity<=0) $checkquantity="E";
				else $checkquantity="C";
			}else {
				$checkquantity="C";
			}

			$arrayname= array("마감","수량");
			$arrayprice=array("E","C");
			$arraydisable=array("true","false");
			$arraybg=array("silver","white");
			$arrayquantity=array("","$quantity");
			for($i=0;$i<2;$i++){?>
						<input type=radio id="idx_checkquantity<?=$i?>" name=checkquantity value="<?=$arrayprice[$i]?>" <?=($checkquantity==$arrayprice[$i])?"checked ":''?> onClick="document.form1.quantity.disabled=<?=$arraydisable[$i]?>;document.form1.quantity.style.background='<?=$arraybg[$i]?>';document.form1.quantity.value='<?=$arrayquantity[$i]?>';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkquantity<?=$i?>">
							<?=$arrayname[$i]?>
						</label>
						&nbsp;
						<?	} ?>
						<input type=text name=quantity size=5 maxlength=5 value="<?=($quantity==0?"":$quantity)?>" class="input">
						개
						<?		} else {
			if ($_data) {
				$quantity=$_data->quantity;
				if($_data->quantity==NULL) $checkquantity="F";
				else if($_data->quantity<=0) $checkquantity="E";
				else $checkquantity="C";
				if($quantity<0) $quantity="";
			} else {
				$checkquantity="C";
			}

			$arrayname= array("품절","무제한","수량");
			$arrayprice=array("E","F","C");
			$arraydisable=array("true","true","false");
			$arraybg=array("silver","silver","white");
			$arrayquantity=array("","","$quantity");
			$cnt = count($arrayprice);			
			for($i=0;$i<$cnt;$i++){
				echo "<input type=radio id=\"idx_checkquantity".$i."\" name=checkquantity value=\"".$arrayprice[$i]."\" ";
				if($checkquantity==$arrayprice[$i]) echo "checked "; echo "onClick=\"document.form1.quantity.disabled=".$arraydisable[$i].";document.form1.quantity.style.background='".$arraybg[$i]."';document.form1.quantity.value='".$arrayquantity[$i]."';\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_checkquantity".$i.">".$arrayname[$i]."</label>&nbsp;&nbsp;";
			}
			echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\" class=\"input\">개";
		}
		if($checkquantity=="C"){
			echo "<script>document.form1.quantity.disabled=false;document.form1.quantity.style.background='white';</script>\n";
		}else{
			echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
		}
?>
					</td>
				</tr>
				<tr class="productItemArea">
					<th>최소구매수량</th>
					<td>
						<input type=text name=miniq value="<?=($miniq>0?$miniq:"1")?>" size=5 maxlength=5 class="input">
						개 이상</td>
					<th>최대구매수량</th>
					<td>
						<input type=radio id="idx_checkmaxq1" name=checkmaxq value="A" <? if (strlen($maxq)==0 || $maxq=="?") echo "checked ";?> onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq1>무제한</label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_checkmaxq2" name=checkmaxq value="B" <? if ($maxq!="?" && $maxq>0) echo "checked"; ?> onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq2>수량</label>
						:
						<input name=maxq size=5 maxlength=5 value="<?=$maxq?>" class="input">
						개 이하 
						<script>
			if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
			else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
			</script></td>
				</tr>
				<tr>
					<th>배송수단선택</th>
					<td colspan="3">
			<?php
				$deli_type_checked = array(4);
				if ($_data->deli_type) {
					$deli_type = explode(',', $_data->deli_type);

					if (in_array('택배', $deli_type)) { $deli_type_checked[0] = "checked='checked'"; }
					if (in_array('퀵서비스', $deli_type)) { $deli_type_checked[1] = "checked='checked'"; }
					if (in_array('방문수령', $deli_type)) { $deli_type_checked[2] = "checked='checked'"; }
					if (in_array('용달', $deli_type)) { $deli_type_checked[3] = "checked='checked'"; }
					if (in_array('장소예약', $deli_type)) { $deli_type_checked[4] = "checked='checked'"; }
				} else {
					$deli_type_checked[0] = "checked='checked'";
				}
			?>
						<input type="checkbox" name="deli_type[]" id="deli_parsel" value="택배" <?=$deli_type_checked[0]?> /><label for="deli_parsel">택배</label> <input type="checkbox" name="deli_type[]" id="deli_quick" value="퀵서비스" <?=$deli_type_checked[1]?> /><label for="deli_quick">퀵서비스</label> <input type="checkbox" name="deli_type[]" id="deli_visit" value="방문수령" <?=$deli_type_checked[2]?> /><label for="deli_visit">방문수령</label> 
						<input type="checkbox" name="deli_type[]" id="deli_car" value="용달" <?=$deli_type_checked[3]?> /><label for="deli_car">용달</label> 
						<input type="checkbox" name="deli_type[]" id="deli_place" value="장소예약" <?=$deli_type_checked[3]?> /><label for="deli_place">장소예약</label>
					</td>
				</tr>
				<tr>
					<th>개별배송비</th>
					<td colspan="3">
						<input type=radio id="idx_deliprtype0" name=deli value="H" <? if(($_data->deli_price<=0 && $_data->deli=="N") || !_isInt($_data->pridx)) echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
						<label style="cursor:hand;margin-right:10px;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype0>
						기본 배송비 <b>유지</b>
						</label>
						<input type=radio id="idx_deliprtype2" name=deli value="F" <? if($_data->deli_price<=0 && $_data->deli=="F") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
						<label style='cursor:hand; margin-right:10px;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype2>
						개별 배송비 <b><font color="#0000FF">무료</font></b>
						</label>
						<input type=radio id="idx_deliprtype1" name=deli value="G" <? if($_data->deli_price<=0 && $_data->deli=="G") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype1>개별 배송비 <b><font color="#38A422">착불</font></b></label>
						<br />
						<input type=radio id="idx_deliprtype3" name=deli value="N" <? if($_data->deli_price>0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype3>개별 배송비 <b><font color="#FF0000">유료</font></b>
							<input type=text name=deli_price_value1 value="<? if($_data->deli_price>0 && $_data->deli=="N") echo $_data->deli_price;?>" size=6 maxlength=6 <? if($_data->deli_price<=0 || $_data->deli=="Y") echo "disabled style='background:silver'";?> class="input">
							원</label>
						&nbsp;<a href="javascript:deli_helpshow();"><img src="images/product_optionhelp3.gif" border="0" align="absmiddle"></a> <br>
						<input type=radio id="idx_deliprtype4" name=deli value="Y" <? if($_data->deli_price>0 && $_data->deli=="Y") echo "checked";?> onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype4>개별 배송비 <b><font color="#FF0000">유료</font></b>
							<input type=text name=deli_price_value2 value="<? if($_data->deli_price>0 && $_data->deli=="Y") echo $_data->deli_price;?>" size=6 maxlength=6 <? if($_data->deli_price<=0 || $_data->deli=="N") echo "disabled style='background:silver'";?> class="input">
							원 (구매수 대비 개별 배송비 증가 : <FONT COLOR="#FF0000"><B>상품구매수×개별 배송비</B></font>)</label>
						&nbsp;<a href="javascript:deli_helpshow();"><img src="images/product_optionhelp3.gif" border="0" align="absmiddle"></a>
						<div id="deli_helpshow_idx" style="display:none;border:1px solid #FF9" class="font_blue"> &nbsp;&nbsp;&nbsp;&nbsp;<b>'개별배송비' 입력 후 '배송비 타입 상품수 대비 배송비 증가' <font color='#0000FF'>체크</font> 예)</b><br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;구매가격&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 10,000원 × 2개구매 = 상품가격 20,000원<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;개별배송비&nbsp;&nbsp;: 3,000원 일때 × 2개구매= 총배송비 6,000원<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;총 결제금액 : 26,000원<br>
							<br>
							&nbsp;&nbsp;&nbsp;&nbsp;<b>'개별배송비' 입력 후 '배송비 타입 상품수 대비 배송비 증가' <font color='#FF0000'>미체크</font> 예)</b><br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;구매가격&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 10,000원 × 2개구매 = 상품가격 20,000원<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;개별배송비&nbsp;&nbsp;: 3,000원(구매수가 2개라도 3,000원 한번만 적용)<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;총 결제금액 : 23,000원 </div>
					</td>
				</tr>
				<tr>
					<th>상품노출등급</th>
					<td class="td_con1" colspan="3">
						<input type=radio id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($_data->group_check!="Y") echo "checked";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">상품노출등급 미지정</label>
						&nbsp;&nbsp;<span class="font_orange">* 상품노출등급 미지정할 경우 모든 비회원, 회원에게 노출됩니다.</span><br>
						<input type=radio id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($_data->group_check=="Y") echo "checked";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">상품노출등급 지정</label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* 회원등급은 <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">회원관리 > 회원등급 설정 > 회원등급 등록/수정/삭제</span></a>에서 관리하세요.</span>
						<div id="group_checkidx" style="<?=($_data->group_check!="Y")?'display:none;':''?>">
							<div style="border:2px #FF7100 solid; background:#FFF7F0;">
								<?	$sqlgrp = "SELECT group_code,group_name FROM tblmembergroup ";
		if(false !== $resultgrp = mysql_query($sqlgrp,get_db_conn())){
			if(mysql_num_rows($resultgrp) < 1){ ?>
								* 회원등급이 존재하지 않습니다.<br>
								* 회원등급은 <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">상품관리 > 카테고리/상품관리 > 상품 거래처 관리</a>에서 등록하세요.
								<? 		}else{
				$grpcnt = 0;
				while($rowgrp = mysql_fetch_object($resultgrp)){ 						
					if($grpcnt!=0 && $grpcnt%4==0){ ?>
								<div style="height:0px; clear:both"></div>
								<? } ?>
								<div style="width:24%; float:left; padding:3px;">
									<input type=checkbox id="group_code_idx<?=$grpcnt?>" name="group_sel_code[]" value="<?=$rowgrp->group_code?>" <?=(strlen($group_code[$rowgrp->group_code])>0?"checked":"")?>>
									<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="group_code_idx<?=$grpcnt?>">
										<?=$rowgrp->group_name?>
									</label>
								</div>
								<?				$grpcnt++;
				}						
				mysql_free_result($resultgrp);			
			}			
		} ?>
								<div style="clear:both; height:1px; display:block"></div>
							</div>
							<?		if($grpcnt>0) { ?>
							<div style="text-align:right; clear:both">
								<input type=checkbox id="group_codeall_idx" onclick="GroupCodeAll(this.checked,<?=$grpcnt?>);">
								<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="group_codeall_idx">일괄선택/해제</label>
							</div>
							<?	} ?>
						</div>
					</td>
				</tr>
				<tr>
					<th>사용자 정의 스펙</th>
					<td colspan="3">
						<input type=radio id="idx_userspec1" name=userspec onclick="userspec_change('N');" value="N" <?if($userspec!="Y") echo "checked";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec1>사용자 정의 스펙 사용안함</label>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_userspec0" name=userspec onclick="userspec_change('Y');" value="Y" <?if($userspec=="Y") echo "checked";?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec0>사용자 정의 스펙 사용함</label>
						<div id="userspecidx" <?=($userspec=="Y"?"":"style='display:none;'")?>>
							<table border="0" cellpadding="0" cellspacing="0">
								
									<td valign="top" bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
										<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
											<tr>
												<td height="7"></td>
											</tr>
											<tr>
												<td align="center" height="30"><b>스<img width="20" height="0">펙<img width="20" height="0">명</b></td>
											</tr>
											<tr>
												<td height="3"></td>
											</tr>
											<tr>
												<td style="padding-left:5px;padding-right:5px;">
													<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
														<tr>
															<td height="1" bgcolor="#DADADA"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td height="5"></td>
											</tr>
											<tr>
												<td>
													<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
														<col width="20">
														
																</col>
														
														<col width="">
														
																</col>
														
														<?for($i=0; $i<$userspec_cnt; $i++) {?>
														<tr>
															<td style="padding:5px;padding-bottom:0px;padding-left:7px;padding-right:2px;" align="center">
																<?=str_pad(($i+1), 2, "0", STR_PAD_LEFT);?>
																.</td>
															<td style="padding:5px;padding-bottom:0px;padding-left:0px;">
																<input name=specname[] value="<?=htmlspecialchars($specname[$i])?>" size=30 maxlength=30 class="input" style="width:100%;">
															</td>
														</tr>
														<?}?>
													</table>
												</td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
										</table>
									</td>
									<td valign="top" bgcolor="#F1FFEF" style="border:2px #57B54A solid;border-left:1px #57B54A solide;">
										<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
											<tr>
												<td height="7"></td>
											</tr>
											<tr>
												<td align="center" height="30"><b>스<img width="20" height="0">펙<img width="20" height="0">내<img width="20" height="0">용</b></td>
											</tr>
											<tr>
												<td height="3"></td>
											</tr>
											<tr>
												<td style="padding-left:5px;padding-right:5px;">
													<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
														<tr>
															<td height="1" bgcolor="#DADADA"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td height="5"></td>
											</tr>
											<?for($i=0; $i<$userspec_cnt; $i++) {?>
											<tr>
												<td style="padding:5px;padding-bottom:0px;">
													<input name=specvalue[] value="<?=htmlspecialchars($specvalue[$i])?>" size=50 class="input" style="width:100%;">
												</td>
											</tr>
											<?}?>
											<tr>
												<td height="10"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<th>검색어</th>
					<td  colspan="3">
						<input name=keyword value="<? if ($_data) echo $_data->keyword; ?>" size=80 maxlength=100 onKeyDown="chkFieldMaxLen(100)" class="input" style="width:100%">
					</td>
				</tr>
				<tr>
					<th>검색 키워드 관리</th>
					<td colspan="3">
						<div class="kw_view">
							<ul>
								<li>
									<span>사용</span>
									<span>분류</span>
									<span>검색키워드</span>
								</li>
								<?
								//등록된 키워드가 있는 경우 
								$codeA = substr($code,0,3)."000000000";
								$codeB = substr($code,0,6)."000000";
								$codeC = substr($code,0,9)."000";
/*
								if($_data->catekeyword){
									
									$arrKwGroup = explode("||",$_data->catekeyword);
									for($i=0;$i<sizeof($arrKwGroup)-1;$i++){
										$arrKw = explode(":",$arrKwGroup[$i]);
										$kg_idx = $arrKw[0];
										$listkeyword = explode(",",$arrKw[1]);
										
										$ksql = "SELECT kwgroup FROM tblkwgroup ";
										$ksql.= "WHERE kg_idx='".$kg_idx."' ";
										$kres = mysql_query($ksql,get_db_conn());
										$krow = mysql_fetch_object($kres);
										
										$use_yn = "Y";
										echo "<li id=\"div_".$kg_idx."\">";
										echo "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$kg_idx."\"></span>";
										echo "<span><input type=\"checkbox\" name=\"".$kg_idx."_useyn\" value=\"Y\" ";
										if ($use_yn=="Y") echo "checked"; else echo "";
										echo ">";
										echo "</span>";
										echo "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup;
										echo "<button type=\"button\" onclick=\"delKwGroup('".$kg_idx."')\" style=\"margin:2px;\">X</button> ";
										echo "</span>";
										
										echo "<span>";
										echo "<input type=\"checkbox\" name=\"ckall_".$kg_idx."\" id=\"ckall_".$kg_idx."\" value=\"Y\" checked onclick=\"javascript:kwcheckAll('".$kg_idx."')\"> 전체";
										
										$ksql2 = "SELECT keyword FROM tblkeyword ";
										$ksql2.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
										$ksql2.= "AND (productcode='' OR productcode='".$_data->productcode."') ";
										$ksql2.= "AND kg_idx='".$kg_idx."' ORDER BY kw_idx";
										$kres2 = mysql_query($ksql2,get_db_conn());
										
										while($krow2 = mysql_fetch_object($kres2)){
											if(strpos($arrKw[1],$krow2->keyword)>-1){
												$checked = "checked";
											}else{
												$checked = "";
											}
											echo "<input type=\"checkbox\" name=\"".$kg_idx."_kw[]\" class=\"ck_".$kg_idx."\" value=\"".$krow2->keyword."\" ".$checked.">";
											echo $krow2->keyword;
											
										}
										echo "</span>";

										echo "<span id=\"".$kg_idx."addDiv\">";
										echo "<button type=\"button\" onclick=\"addKwText('".$kg_idx."')\">추가</button>";
										echo "</span>";
										echo "<span id=\"".$kg_idx."addDiv2\" style=\"display:none\">";
										echo "<input type=\"text\" id=\"".$kg_idx."_kw_text\" name=\"".$kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\">";
										echo "<button type=\"button\" onclick=\"insertKwText('".$kg_idx."')\">추가</button>";
										echo "<button type=\"button\" onclick=\"cancelKwText('".$kg_idx."')\">취소</button>";
										echo "</span>";
										echo "</li>";
									}//end for($i)

								}else{
									*/
								$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
								$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
								$ksql.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
								if($_data->catekeyword){
									$ksql.= "AND (productcode='' OR productcode='".$_data->productcode."') ";
								}else{
									$ksql.= "AND productcode='' ";
								}
								$ksql.= "AND use_yn='Y' GROUP BY kw.kg_idx";

								$kres = mysql_query($ksql,get_db_conn());

								while($krow = mysql_fetch_object($kres)){
									echo "<li id=\"div_".$krow->kg_idx."\">";
									echo "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$krow->kg_idx."\"></span>";
									//echo "<span><input type=\"checkbox\" name=\"".$krow->kg_idx."_useyn\" value=\"Y\" ";
									//if ($krow->use_yn=="Y") echo "checked"; else echo "";
									//echo ">";
									echo "<span><button type=\"button\" onclick=\"delKwGroup('".$krow->kg_idx."')\" style=\"margin:2px;\">X</button></span>";
									echo "</span>";
									echo "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup;
									//echo "<button type=\"button\" onclick=\"delKwGroup('".$krow->kg_idx."')\" style=\"margin:2px;\">X</button> ";
									echo "</span>";
																				
									echo "<span id=\"".$krow->kg_idx."_kwlist\">";
									echo "<input type=\"checkbox\" name=\"ckall_".$krow->kg_idx."\" id=\"ckall_".$krow->kg_idx."\" value=\"Y\" onclick=\"javascript:kwcheckAll('".$krow->kg_idx."')\"> 전체";
									
/*									
									$arrKwGroup = explode("||",$_data->catekeyword);
									for($i=0;$i<sizeof($arrKwGroup)-1;$i++){
										$arrKw = explode(":",$arrKwGroup[$i]);
										$kg_idx = $arrKw[0];
										$listkeyword = explode(",",$arrKw[1]);
*/

									$ksql2 = "SELECT kw_idx,keyword FROM tblkeyword ";
									$ksql2.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
									if($_data->catekeyword){
										$ksql2.= "AND (productcode='' OR productcode='".$_data->productcode."') ";
									}else{
										$ksql2.= "AND productcode='' ";
									}
									$ksql2.= "AND kg_idx='".$krow->kg_idx."' ORDER BY kw_idx";
									$kres2 = mysql_query($ksql2,get_db_conn());
									while($krow2 = mysql_fetch_object($kres2)){
										if(strpos($_data->catekeyword,$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword)>-1){
											$checked = "checked";
										}else{
											$checked = "";
										}
										
										echo "<input type=\"checkbox\" name=\"".$krow2->kw_idx."_kw[]\" class=\"ck_".$krow->kg_idx."\" value=\"".$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword."\"  onclick=\"addcatekw('".$krow2->kw_idx."','".$krow->kwgroup."','".$krow2->keyword."')\" ".$checked.">";
										echo $krow2->keyword;

										//echo "<input type=\"hidden\" name=\"".$krow->kg_idx."_kw[]\" value=\"".$krow2->keyword."\">";
										//echo "<button type=\"button\" onclick=\"delKwText(this)\" style=\"margin:2px;\">X</button> ";
										
									}
									echo "</span>";

									echo "<span id=\"".$krow->kg_idx."addDiv\">";
									echo "<button type=\"button\" onclick=\"addKwText('".$krow->kg_idx."')\">추가</button>";
									echo "</span>";
									echo "<span id=\"".$krow->kg_idx."addDiv2\" style=\"display:none\">";
									echo "<input type=\"hidden\" id=\"".$krow->kg_idx."_kwgroup\" name=\"".$krow->kg_idx."_kwgroup\"\" value=\"".$krow->kwgroup."\">";
									echo "<input type=\"text\" id=\"".$krow->kg_idx."_kw_text\" name=\"".$krow->kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\">";
									echo "<button type=\"button\" onclick=\"insertKwText('".$krow->kg_idx."')\">추가</button>";
									echo "<button type=\"button\" onclick=\"cancelKwText('".$krow->kg_idx."')\">취소</button>";
									echo "</span>";
									echo "</li>";
								}
								//}
								?>
							</ul>
						</div>
						추가등록
						<div class="div_kw">
							<select name="kw_group" id="kw_group" onchange="javascript:addKwSelect(this.value,this.options[this.selectedIndex].text)">
								<option value="">키워드분류를 선택하세요</option>
								<?
								$kwsql = "SELECT * FROM tblkwgroup ";
								$kwres = mysql_query($kwsql,get_db_conn());
								while ($kwrow = mysql_fetch_object($kwres)) {
									echo "<option value=\"".$kwrow->kg_idx."\">".$kwrow->kwgroup."</option>";
								}
								?>
							</select>
							<button type="button" onclick="addKwGroup()">추가</button>
						</div>
						<div class="div_kw2" style="display:none">
							<input type="text" name="kwgroup" id="kwgroup">
							<button type="button" onclick="addKwSend()">확인</button>
							<button type="button" onclick="addKwCancel()">취소</button>
						</div>
						<div class="div_keywordlist">
							<?
							if($_data->catekeyword){
								$arrcatekeyword = explode('||',$_data->catekeyword);
								for($i=0;$i<sizeof($arrcatekeyword);$i++){
									
									$catekw = explode(':',$arrcatekeyword[$i]);
									echo "<span id=\"div_".$catekw[0]."\">";
									echo "<input type=\"hidden\" name=\"kw_idx[]\" value=\"".$catekw[0]."\">";
									echo $catekw[1].":".$catekw[2];
									echo "<button type=\"button\" onclick=\"delcatekw(this)\" style=\"margin:2px;\">x</button> &nbsp;";
									echo "</span>";
								}
							}
							?>
						</div>
					</td>
				</tr>

				<? if($gongtype=="N"){?>
				<tr>
					<th>특이사항</th>
					<td colspan="3">
						<input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=43 maxlength=200 onKeyDown="chkFieldMaxLen(200)" class="input">
						&nbsp;<span class="font_orange">* 상품의 특이사항을 입력해 주세요.</span></td>
				</tr>
				<? } else { ?>
				<tr>
					<th>공구 판매수량 표시</th>
					<td colspan="3">
						<input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=35 maxlength=200 class="input">
						&nbsp;<span class="font_orange">(예: 한정판매 : 50개, 판매수량 : 100개)</span></td>
				</tr>
				<? } ?>
				<? if(false && strlen($_data->productcode)==18){?>
				<tr>
					<th>태그 관리</th>
					<td colspan="3">
						<DIV id="ProductTagList" name="ProductTagList" style="padding:5px;width:600px;height:68px;word-spacing:7px;background:#fafafa"> 태그를 불러오고 있습니다. </DIV>
						<script>loadProductTagList('<?=$_data->productcode?>');</script></td>
				</tr>
				<? }?>
				<tr>
					<th class="reqFld">상품리스트 및<br/>상품상세대표이미지 </th>
					<td colspan="3">
						<input type=file name="userfile" onchange="document.getElementById('size_checker').src=this.value;" style="WIDTH: 400px" class="input">
						<input type=text name="userfile_url" value="<?=$userfile_url?>" style="WIDTH: 400px; display:none" class="input">
						<span class="font_orange">(권장이미지 : 550X550, 이미지를 등록하셔야 상품리스트 이미지가 출력됩니다.)</span> <br>
						<input type="hidden" id="idx_imgcheck1" name="imgcheck" value="Y" />					
						<input type=hidden name="vimage" value="<?=$_data->maximage?>">				
						<input type=hidden name="vimage2" value="<?=$_data->minimage?>">
						<input type=hidden name="vimage3" value="<?=$_data->tinyimage?>">
						<?
		if ($_data) {
			if (strlen($_data->maximage)>0 && file_exists($imagepath.$_data->maximage)==true) {
				echo "<br><img src='".$imagepath.$_data->maximage."?t=".time()."' height=100 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$_data->maximage."'>";
				echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('1')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
			} else {
				echo "<br><img src=\"images/space01.gif\">";
			}
		}
?>
					</td>
				</tr>
				<tr>
					<th class="reqFld">추가 이미지&amp;컨텐츠 </th>
					<td colspan="3">
					<script language="javascript" type="text/javascript">
						function bookingSchedulePop (pridx,isadmin){											
							window.open("/admin/product_ext/multicontents.php?pridx="+pridx,"ContentMG","width=600,height=600,scrollbars=yes");
						}
					</script>

						<input type="button" value="추가 컨텐츠 및 이미지 관리하기" onclick="bookingSchedulePop('<?=_isInt($_data->pridx)?$_data->pridx:$chkstamp?>','1');">
					</td>
				</tr>
				<!-- wide 이미지 -->
				<tr style="display:none">
					<th class="reqFld">모바일샵 이미지</th>
					<td class="td_con1" colspan="3" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;">
						<input type="file" name="wideimage" style="WIDTH: 400px" class="input">
						<input type="hidden" name="attechwide" value="<?=$_data->wideimage?>">
						<span class="font_orange">(권장이미지 : 400X240)</span>
						<? if(is_file($savewideimage.$_data->wideimage)){?>
						<br/>
						<img src="<?=$savewideimage.$_data->wideimage?>?t=<?=time()?>" width="150"/> <a href="JavaScript:DeletePrdtImg('4')"><img src="images/icon_del1.gif" align="bottom" border="0"></a>
						<? }?>
						<br/>
						* 모바일샵 메인 디스플레이 타입 중 리스트타입 전용 이미지를 첨부하는 기능입니다. <br/>
						* 해당 이미지를 첨부하지 않은 상태에서 모바일샵 메인 디스플레이 타입을 리스트로 적용 할 경우 상품 이미지가 노출 되지 않습니다. <br/>
						* 리스트이미지 변경을 원하실 경우 재 첨부를 하신 뒤 수정 하시면 변경 됩니다. </td>
				</tr>
				<tr>
					<th class="reqFld">상품 상세내역 입력</th>
					<td colspan="3">
						<? if($predit_type=="Y" && false){?>
						<input type=radio id="idx_checkedit1" name=checkedit checked onClick="JavaScript:htmlsetmode('wysiwyg',this)">
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_checkedit1>웹편집기로 입력하기(권장)</label>
						&nbsp;&nbsp;
						<input type=radio id="idx_checkedit2" name=checkedit onClick="JavaScript:htmlsetmode('textedit',this);">
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_checkedit2>직접 HTML로 입력하기</label>
						<? } ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=checkbox id="idx_localsave" name=localsave value="Y" <?=($localsave=="Y"?"checked":"")?> onClick="alert('상품 상세내역에 링크된 타서버 이미지를 본 쇼핑몰에 저장 후 링크를 변경하는 기능입니다.')">
						<label style='cursor:hand;' onMouseOver="style.textDecoration='none'" onMouseOut="style.textDecoration='none'" for=idx_localsave><span class="font_orange"><B>타서버 이미지 쇼핑몰에 저장</B></span></label>
					</td>
					
						</td>
				</tr>
				<tr>
					<td colspan="4">

						<!--
						<textarea wrap=off style="WIDTH: 100%; HEIGHT: 300px" name="content" lang="ej-editor1"><?=htmlspecialchars($_data->content)?></textarea>
						-->



						<!-- naver editor -->
						<script type="text/javascript" src="<?=$Dir?>navereditor/js/HuskyEZCreator.js" charset="utf-8"></script>
						<textarea name="content" id="ir1" rows="10" cols="100" style="width:100%; height:412px; display:none;"><?=htmlspecialchars($_data->content)?></textarea>

						<script type="text/javascript">
							var oEditors = [];

							// 추가 글꼴 목록
							//var aAdditionalFontSet = [["MS UI Gothic", "MS UI Gothic"], ["Comic Sans MS", "Comic Sans MS"],["TEST","TEST"]];

							nhn.husky.EZCreator.createInIFrame({
								oAppRef: oEditors,
								elPlaceHolder: "ir1",
								sSkinURI: "<?=$Dir?>navereditor/SmartEditor2Skin.html",
								htParams : {
									bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
									bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
									bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
									//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
									fOnBeforeUnload : function(){
										//alert("완료!");
									}
								}, //boolean
								fOnAppLoad : function(){
									//예제 코드
									//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
								},
								fCreator: "createSEditor2"
							});
						</script>
						<!-- naver editor -->



						<img id="size_checker" style="display:none;"><img id="size_checker2" style="display:none;"><img id="size_checker3" style="display:none;">
					</td>
				</tr>
			</tbody>
		</table>
		<? /* 추가 정보 입력 */ ?>
		<table border="0" cellpadding="0" cellspacing="0" class="inputTbl" style=" margin-top:20px;">
			<caption><IMG SRC="images/design_eachjoin_stitle2.gif"  ALT=""></caption>
			<tbody>		
				<tr class="productItemArea">
					<th>옵션 타입 선택</th>
					<td>
						<input type=radio id="idx_searchtype0" name=searchtype style="border:none" onclick="ViewLayer('layer0')" value="0" <?if($searchtype=="0") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype0>옵션정보 없음</label>
						<img width=10 height=0>
						<input type=radio id="idx_searchtype1" name=searchtype style="border:none" onclick="ViewLayer('layer1');alert('옵션1과 옵션2는 최대 10개로\n각 옵션별 수량조절이 가능하게 됩니다.\n수정시 기존의 그이상의 옵션들은 삭제됩니다.');" value="1" <?if($searchtype=="1") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>상품 옵션 + <font color=#FF0000>재고관리</font></label>
						<a href="JavaScript:optionhelp()"><img src="images/product_optionhelp3.gif" align=absmiddle border=0></a> <img width=10 height=0>
						<input type=radio id="idx_searchtype2" name=searchtype style="border:none" onclick="ViewLayer('layer2')" value="2" <?if($searchtype=="2") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>상품 옵션 무제한 등록</label>
						<? if($gongtype=="N" && (int)$_data->vender==0){ ?>
						<img width=10 height=0>
						<input type=radio id="idx_searchtype3" name=searchtype style="border:none" onclick="ViewLayer('layer3')" value="3" <?if($searchtype=="3") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype3>옵션그룹</label>
						<? } ?>						
						<div id="layer0" style="margin-left:0; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;"></div>
						<div id="layer1" style="margin-left:0; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>								
								<?
									$optionarray1=explode(",",$_data->option1);
									$option_price=explode(",",$_data->option_price);
									$optionarray2=explode(",",$_data->option2);
									$option_quantity_array=explode(",",$_data->option_quantity);
									$optnum1=count($optionarray1)-1;
									$optnum2=count($optionarray2)-1;
						
									$optionover="NO";
									if($optnum1>10){
										$optnum1=10;
										$optionover="YES";
									}
									if($optnum2>10){
										$optnum2=10;
										$optionover="YES";
									}
									if($optnum1>0 && strlen($_data->option_quantity)==0) $optionover="YES";
									if($optnum2<=1) $optnum2=1;
								?>
								<tr>
									<th>상품옵션 속성명</th>
									<td><b>옵션1 속성명</b> : 
										<input name=option1_name value="<? if (strlen($_data->option1)>0) echo htmlspecialchars($optionarray1[0]); ?>" size=20 maxlength=20 class="input">
										&nbsp;&nbsp;&nbsp;&nbsp;<b>옵션2 속성명 : 
										<input name=option2_name value="<? if (strlen($_data->option2)>0) echo htmlspecialchars($optionarray2[0]); ?>" size=20 maxlength=20 class="input">
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:3pt; padding-bottom:3pt;" class="notice_blue">
										1) 옵션가격 입력시 판매가격은 무시되고 옵션가격으로 구매가 진행됩니다.<br>
										2) 판매상품 품절일 경우 옵션 재고수량이 남아 있더라도 상품구매는 진행되지 않습니다.<br>
										&nbsp;&nbsp;&nbsp;옵션 재고수량으로만 상품 관리를 할 경우 판매상품 재고수량을 무제한으로 설정해 주세요.<br>
										3) 옵션 재고수량 미입력시 옵션 재고수량은 무제한 상태가 되며 "0" 입력시 옵션 재고수량은 품절 상태가 됩니다.
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<TABLE cellSpacing=0 cellPadding=0 width="754px" border="0" style="background:#F9F9F9">
											<tr>
												<td width="80px" rowspan="2" style=" border:2px solid #FF7100; border-bottom:1px solid #efefef; font-weight:bold; text-align:center">옵션1 속성</td>
												<td width="80px" rowspan="2" style=" border:2px solid #0071C3; border-bottom:1px solid #efefef; font-weight:bold; text-align:center">가격</td>
												<td width="80px" colspan="10" style="border-top:2px solid #57b54a; font-weight:bold; text-align:center">옵션2 속성</td>
											</tr>
											<tr>
											<?	for($i=1;$i<=10;$i++){ ?>
												<td style="text-align:center;border-bottom:2px solid #57b54a; "><input type=text name="optname2" value="<?=htmlspecialchars($optionarray2[$i])?>" size=8 class="input"></td>
											<?	} ?>
											</tr>
											<? for($i=1;$i<=10;$i++){ ?>
											<tr>
												<td style="border-left:2px solid #FF7100;border-right:2px solid #FF7100; <?=$i==10?'border-bottom:2px solid #FF7100;':''?>"><input type=text name=optname1 value="<?=trim(htmlspecialchars($optionarray1[$i]))?>" size=8 class="input"></td>
												<td style="border-left:2px solid #0071C3;border-right:2px solid #0071C3;<?=$i==10?'border-bottom:2px solid #0071C3;':''?>"><input type=text name=optprice size=8  value="<?=$option_price[$i]?>" onkeyup="strnumkeyup(this)" class="input"></td>
												<? for($j=0;$j<10;$j++){ ?>
												<td style="text-align:center"><input type=text name=optnumvalue[<?=$j?>][<?=$i?>] value="<?=$option_quantity_array[$j*10+$i+1]?>" size=8 maxlength=3 onkeyup="strnumkeyup(this)" class="input"></td>
												<? } ?>
											</tr>
											<? } ?>
										</TABLE>
									</td>
								</tr>
							</table>							
						</div>
						<div id="layer2" style="margin-left:0; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
									<th>옵션1</th>
									<td>
						<?
							$option1=$optname1="";
							if ($_data) {
								if (strlen($_data->option1)>0) {
									$tok = strtok($_data->option1,",");
									$optname1=$tok;
									$tok = strtok("");
									$option1=$tok;
								}
							}
			?>
										<span style="width:100px; display:inline-block; margin-right:5px;">1)속성명</span><input name=toptname1 value="<? if ($_data && strlen($_data->option1)>0) echo $optname1; ?>" size=50 maxlength=20 class="input"><br />
										<span style="width:100px; display:inline-block; margin-right:5px;">2)속성</span><input name=toption1 value="<? if ($_data && strlen($_data->option1)>0) echo htmlspecialchars($option1); ?>" size=50 maxlength=230 class="input"><br />
										<span>
											* 옵션의 속성명으로 색상 또는 사이즈 또는 용량 등을 입력해서 사용하세요.<br>
											* 속성은 속성명에 대한 세부내용을 입력합니다.<br>
											&nbsp;&nbsp;&nbsp;예)빨강,파랑,노랑 또는 95,100,105 와 같이 컴마(,)로 구분하여 공백없이 입력합니다.
										</span>
									</td>
								</tr>								
								<tr>
									<th>옵션1 가격</th>
									<td>
										<? if($gongtype=="N"){?>
										<input name=toption_price value="<? if ($_data) echo $_data->option_price; ?>" size=50 maxlength=250 class="input">
										&nbsp;<span class="font_orange"><b>예) 1000,2000,3000</b></span><br>
										* 옵션1 가격 입력시 판매가격은 무시됩니다.<br>
										* 옵션1 가격 입력시 판매가격 대신 첫번째 가격이 판매가격으로 사용됩니다.<br>
										* 카테고리내 상품 출력시 "판매가격 (기본가)"로 표기 됩니다.<br>
										* 메세지 변경은
										<?=($popup=="YES"?"":"<A HREF=\"javascript:parent.parent.topframe.GoMenu(1,'shop_mainproduct.php');\">")?>
										<span class="font_blue">상점관리 > 쇼핑몰 환경설정 > 상품 진열 기타 설정</span></A> 에서 변경 가능.
										<? } else { ?>
										가격 고정형 공동구매의 경우 옵션1 가격은 지원하지 않습니다.
										<input type=hidden name=toption_price>
										<? } ?>
									</td>
								</tr>
								<tr>
									<th>옵션2</th>
									<td class="td_con1">
					<?
						$option2=$optname2="";
						if ($_data) {
							if (strlen($_data->option2)>0) {
								$tok = strtok($_data->option2,",");
								$optname2=$tok;
								$tok = strtok("");
								$option2=$tok;
							}
						}
			?>
										<span style="width:100px; display:inline-block; margin-right:5px;">1)속성명</span><input name=toptname2 value="<? if ($_data && strlen($_data->option2)>0) echo $optname2; ?>" size=50 maxlength=20 class="input"><br />
										<span style="width:100px; display:inline-block; margin-right:5px;">2)속성</span><input name=toption2 value="<? if ($_data && strlen($_data->option2)>0) echo htmlspecialchars($option2); ?>" size=50 maxlength=230 class="input">
										<br />
										<span>* 옵션1 등록 방법과 같으나 "<B>옵션1 가격</B>"과는 무관합니다.</span>
									</td>
								</tr>
							</table>
						</div>
						<div id="layer3" style="margin-left:0; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
							<? if($gongtype=="N"){?>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>								
								<tr>
									<th>옵션그룹 선택</th>
									<td>
										<select name=optiongroup style="width: 70%" class="select">
											<?
						$sqlopt = "SELECT option_code,description FROM tblproductoption ";
						$resultopt = mysql_query($sqlopt,get_db_conn());
						$optcnt=0;
						while($rowopt = mysql_fetch_object($resultopt)){
							if($optcnt++==0) echo "<option value=0>옵션그룹을 선택하세요.";
							echo "<option value=\"".$rowopt->option_code."\"";
							if($optcode==$rowopt->option_code) echo " selected";
							echo ">".$rowopt->description."</option>";
						}
						mysql_free_result($resultopt);
						if($optcnt==0) echo "<option value=0>등록하신 옵션그룹이 없습니다.</option>";
			?>
										</select>
										<?if($popup!="YES"){?>
										<A HREF="javascript:parent.location='product_option.php';"><B><img src="images/btn_option.gif" width="105" height="18" border="0" hspace="2" align=absmiddle></B></A>
										<?}?>
										<?if($optcnt==0) echo "<script>document.form1.optiongroup.disabled=true;</script>";?>
										<br>
										* (상품가격+옵션) 변경가격 사용시 옵션그룹을 이용해 주세요. <br>
										* 옵션그룹 사용시 옵션1과 옵션2는 자동 삭제됩니다. <br>
										* 옵션그룹 선택시 해당 옵션그룹에 등록된 상품옵션을 확인할 수 있습니다. </td>
								</tr>
							</TABLE>
							<? }?>
						</div>
					</td>
				</tr>
				<tr>
					<th>아이콘 꾸미기</td>
					<td>
						<?
						$iconarray = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28");						
						for($i=0;$i<count($iconarray);$i++) { ?>						
							<span style="width:14%; display:inline-block"><input type=checkbox name=icon onclick="CheckChoiceIcon('<?=$i?>')" value="<?=$iconarray[$i]?>" <? if($iconvalue2[$iconarray[$i]]=="Y") echo "checked"; ?>><img src="<?=$Dir?>images/common/icon<?=$iconarray[$i]?>.gif" border=0 align=absmiddle></span>
				<?		} 
						$totaliconnum = $i;			?>
				
						<div style="clear:both; padding:5px 0px; border:1px solid #FF9933">
							<span style="width:14%; display:inline-block; text-align:center"><b><span class="font_orange">내 아이콘</span></b></span>
							<?
						$iconpath=$Dir.DataDir."shopimages/etc/";
						$usericon = array("U1","U2","U3","U4","U5","U6");
						$cnt=0;
						
						for($i=0;$i<count($usericon);$i++){
							if(file_exists($iconpath."icon".$usericon[$i].".gif")){
								$cnt++; ?>
								<span style="width:14%; display:inline-block"><input type=checkbox name=icon onclick=CheckChoiceIcon('<?=$totaliconnum?>') value="<?=$usericon[$i]?>" <? if($iconvalue2[$usericon[$i]]=="Y") echo "checked";?>><img src="<?=$iconpath?>icon<?=$usericon[$i]?>.gif" border=0 align=absmiddle></span>
							<?
								$totaliconnum++;
							}
						}
						if($cnt==0) {
							echo '<span style=" text-align:center"><font color=red>등록된 내 아이콘이 없습니다.</font></span>';
						} 
?>
							<div style="background:#FF9933;padding-left:5pt; color:white;letter-spacing:-0.5;">* 한 상품에 3개까지 아이콘을 사용할 수 있습니다.<br>	* <b>아이콘 등록은 6개 까지 등록</b> 가능합니다.<br />
							<A href="JavaScript:IconMy()"><IMG src="images/productregister_iconinsert.gif" align=absMiddle border=0 width="120" height="20"></A>&nbsp;<A href="JavaScript:IconList()"><IMG src="images/productregister_icondown.gif" align=absMiddle border=0 width="98" height="20"></A>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>상품정보고시</th>
					<td> 상품구분 선택 :
						<select name="gosiTemplet" class="select">
							<option value="">템플릿 리스트 로딩중</option>
						</select>
						<div><span class="font_orange"> ＊ 항목명 또는 내용 중 한 부분이라도 내용이 없을경우 해당 항목은 등록되지 않습니다.<br>
						＊ 상품 구분선택을 통한 정보고시 내용은 기본 설정된 각 부분별 내용으로 필요시 수정이 가능합니다.<br>
						＊ 정보고시 내용 변경시 기존 등록 내용은 초기화되며, 상품 정보 저장시 적용됩니다. </span></div>
						<style type="text/css">
								.dtitleTd{ padding:0px 0px 0px 10px; background-color:#f5f5f5; }
								.daccTd{ padding:8px 0px 8px 10px; }
								.dbtnTd{ padding:10px 0px 10px 0px; }
								.dtitleInput{ width:96%; border:1px solid #ccc; font-family:돋움; letter-spacing:-1px; }
								.ditemTextarea{ width:98%; line-height:18px;}
							</style>
						<script language="javascript" type="text/javascript">

								function addGosiItem(el,itm){
									var str = '<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>';
									str += '<tr>';
									str +=	  '<td class="dtitleTd"><input type="hidden" name="didx[]" value="" /><input type="text" name="dtitle[]" value="'+((itm && itm.title)?itm.title:'')+'" class="dtitleInput" /></td>';
									str +=	  '<td width="60%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"></textarea></td>';

									if(itm && itm.desc){
										 str +=	  '<td width="90" class="dbtnTd" rowspan="2"><button class="gosiDef">[상세정보별도표기]</button><br /><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td></tr>';
										 str += '<tr><td colspan="2" class="daccTd"><span class="font_orange">* '+itm.desc+'</span></td></tr>';
									}else{
										 str +=	  '<td class="dbtnTd"><button class="gosiDef">[상세정보별도표기]</button><br /><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td></tr>';
									}

									if(el){
										$tel = $(el).parent().parent();
										if($j($tel).next().find('td.daccTd')){
											$tel = 	$j($tel).next();
										}
										 $j($tel).after(str);
									}else{
										 if($j('#detailTable').find('tr').length <1){
											  $j('#detailTable').append('<tbody>'+str+'</tbody>');
										 }else{
											  $j('#detailTable').find('tr:last').after(str);
										 }
									}
									if($j('#detailTable').css('display') == 'none') $j('#detailTable').css('display','');
									parent_resizeIframe('AddFrame');
									$j('#ingosiDefTxtDiv').css('display','');
								}

								function removeGosiItem(el){
									//$j(el).parent().parent().remove();

									$ptr = $j(el).parent().prev();
									$ptr.prev().remove();
									$prt.remove();
									if($j('#detailTable').find('tr').length <1){
										 $j('#detailTable').css('display','none');
										 $j('#ingosiDefTxtDiv').css('display','none');
									}
								}

								function gosiDesStrset(el){
									if(el == true){
										$j('.ditemTextarea').text('상세정보별도표기');
									}else if(el){
										var $tel = $j(el).parent().parent().find('.ditemTextarea');
										$j($tel).text('상세정보별도표기');
									}
								}

								$j(function(){
									$j('.gosiDef').live("click",function(){
										gosiDesStrset($j(this));
									});

									$j.post('/lib/ext/getbyjson.php',{'act':'getProductGosiTitles'},
										 function(data){
											  if(data.err != 'ok'){
												   alert(data.err);
											  }else{
												   $j('select[name=gosiTemplet]').find('option').remove();
												   $j('select[name=gosiTemplet]').append('<option value="">== 상품 구분 선택 ==</option>');
												   $j.each(data.items,function(idx,itm){
														$j('select[name=gosiTemplet]').append('<option value="'+itm.idx+'">'+itm.title+'</option>');
												   });
												   $j('select[name=gosiTemplet]').append('<option value="-1">직접 입력</option>');
											  }
									},'json');

									$j(document).on('change','select[name=gosiTemplet]',function(){
										 var idx = $j(this).val();
										 if(idx == '-1'){
											  addGosiItem(null,null);
										 }else{
											  $j.post('/lib/ext/getbyjson.php',{'act':'getProductGosiItems','idx':idx},
												   function(data){
														if(data.err != 'ok'){
															 alert(data.err);
														}else{
															 $j('#detailTable').find('tr').remove();
															 $j.each(data.items,function(idx,itm){
																  addGosiItem(null,itm);
															 });
														}
												   },'json');
										 }
									});

									$j(document).on('click','.ditemAddBtn',function(){
										 addGosiItem(this,null);
									});

									$j(document).on('click','.ditemDelBtn',function(){
										 removeGosiItem(this);
									});

								});
							</script>
						<?
						 $detialItems = _getProductDetails($_data->pridx);
						 ?>
						<div id="ingosiDefTxtDiv" style="text-align:right;display:<?=(count($detialItems)>0)?'':'none'?>;"><input type="checkbox" name="gosiAllDef" onclick="javascript:gosiDesStrset(true);" value="" />모든 상품정보 "상세정보 별도표기" 선택</div>
						<table width="98%" border="0" cellpadding="0" cellspacing="0" id="detailTable" style="margin:0px 10px 0px 15px; display:<?=(count($detialItems)>0)?'':'none'?>; border-bottom:1px solid #dddddd">
							<? if(count($detialItems)>0){
											foreach($detialItems as $ditem){ ?>
							<tr>
								<td class="dtitleTd">
									<input type="hidden" name="didx[]" value="<?=$ditem['didx']?>" />
									<input type="text" name="dtitle[]" value="<?=$ditem['dtitle']?>" class="dtitleInput" />
								</td>
								<td width="65%" class="td_con1">
									<textarea name="dcontent[]" class="ditemTextarea"><?=$ditem['dcontent']?>
</textarea>
								</td>
								<td width="90" class="dbtnTd">
									<button class="gosiDef">[상세정보별도표기]</button>
									<br />
									<img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br>
									<img src="images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td>
							</tr>
							<tr>
								<td colspan="3" height="1" bgcolor="#dddddd"></td>
							</tr>
							<?	 } // end foreach
								} // end if
						?>
						</table>
					</td>
				</tr>
				<tr>
					<th>거래 업체 선택</th>
					<td>
						<select name=bisinesscode class="select">
							<option value="0"> -------- 거래업체를 선택하세요. -------- </option>
						<?
							$sqlbiz = "SELECT companycode,companyviewval FROM tblproductbisiness ";
							$resultbiz = mysql_query($sqlbiz,get_db_conn());
							$bizcnt=0;
							while($rowbiz = mysql_fetch_object($resultbiz)){
								echo "<option value=\"".$rowbiz->companycode."\"";
								if($_data->bisinesscode==$rowbiz->companycode) echo " selected";
								echo ">".$rowbiz->companyviewval."</option>\n";
								$bizcnt++;
							}
							mysql_free_result($resultbiz);
							if($bizcnt==0) echo "<option value=\"0\">등록하신 거래업체가 없습니다.</option>";
						?>
						</select>
						<br>
						<span class="font_orange">* 거래 업체 등록은 <a href="javascript:parent.parent.topframe.GoMenu(4,'product_business.php');"><span class="font_blue">상품관리 > 카테고리/상품관리 > 상품 거래처 관리</span></a>에서 등록하세요.</span>
						<input type=hidden name=old_display value="<?=$_data->display?>">
						</td>						
				</tr>
				<tr>
					<th>상품기타설정</th>
					<td>	
						<? if ($card_splittype=="O") { ?>							
							<input type=checkbox id="idx_setquota1" name=setquota value="Y" <? if ($_data) { if ($setquota=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_setquota1>상점부담 무이자</label>
							<span class="font_orange">(결제금액/무이자할부개월은 <a  href="shop_payment.php"><b>결제관련기능설정</b></a>과 동일)</span><br />
						<? } ?>
							<input type=checkbox id="idx_deliinfono1" name=deliinfono value="Y" <? if ($_data) { if ($deliinfono=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliinfono1>배송/교환/환불정보 노출안함</label>
							<font color=#AA0000>(상품상세화면 하단에 배송/교환/환불정보가 노출안됨)</font>
					</td>
				</tr>
		<? if($sns_ok == "Y"){	?>
				<tr>
					<th>SNS 사용여부</th>
					<td>
						<input type=radio id="sns_state1" name=sns_state value="Y" <? if ($_data) { if ($_data->sns_state=="Y") echo "checked"; }  ?> onclick="ViewSnsLayer('block')" >
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=sns_state1>사용함</label>
						&nbsp;
						<input type=radio id="sns_state2" name=sns_state value="N" <? if ($_data) { if ($_data->sns_state !="Y") echo "checked"; } else echo "checked"; ?> onclick="ViewSnsLayer('none')" >
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=sns_state2>사용안함</label>
					</td>
				</tr>
<? /* if($arSnsType[0] =="B"){	?>
				<tr id ="sns_optionWrap" style="display:none;">
					<td colspan=2>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width=160>
							
									</col>
							
							<col>
							
									</col>
							
							<col width=160>
							
									</col>
							
							<col>
							
									</col>
							
							<tr>
								<td colspan=4 background="images/table_con_line.gif"></td>
							</tr>
							<tr>
								<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">추천인 적립금(률)</td>
								<td class="td_con1">
									<input name=sns_reserve1 value="<?=$_data->sns_reserve1?>" size=10 maxlength=6 class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve1_type.value);">
									<select name="sns_reserve1_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
										<option value="N"<?=($_data->sns_reserve1_type!="Y"?" selected":"")?>>적립금(￦)</option>
										<option value="Y"<?=($_data->sns_reserve1_type!="Y"?"":" selected")?>>적립률(%)</option>
									</select>
								</td>
								<td class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">비추천인 적립금(률)</td>
								<td class="td_con1">
									<input name=sns_reserve2 value="<?=$_data->sns_reserve2?>" size=10 maxlength=6 class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve2_type.value);">
									<select name="sns_reserve2_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
										<option value="N"<?=($_data->sns_reserve2_type!="Y"?" selected":"")?>>적립금(￦)</option>
										<option value="Y"<?=($_data->sns_reserve2_type!="Y"?"":" selected")?>>적립률(%)</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?	} */
			}?>
				<? /*
				<tr>
					<th>선물하기 사용여부</th>
					<td>
						<input type=radio id="present_state1" name=present_state value="Y" <? if ($_data) { if ($_data->present_state=="Y") echo "checked"; } ?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=present_state1>사용함</label>
						&nbsp;
						<input type=radio id="present_state2" name=present_state value="N" <? if ($_data) { if ($_data->present_state!="Y") echo "checked"; } else echo "checked"; ?>>
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=present_state2>사용안함</label>
						<input type=hidden  name="pester_state" value="Y"  /> <!-- 조르기 무조건 사용 -->
					</td>
				</tr>*/ ?>
<?	if(false && $arRecomType[0] =="B" && $arRecomType[1] == "B"){ ?>
				<tr>
					<th>첫구매시 추천인 적립</th>
					<td>
						<input name=first_reserve value="<?=$_data->first_reserve?>" size=10 maxlength=6 class="input" style="width:20%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.first_reserve_type.value);">
						<select name="first_reserve_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
							<option value="N"<?=($_data->first_reserve_type!="Y"?" selected":"")?>>적립금(￦)</option>
							<option value="Y"<?=($_data->first_reserve_type!="Y"?"":" selected")?>>적립률(%)</option>
						</select>
						<font color="#ff0000"> *sns홍보를 통해 첫구매에도 적용</font></td>
				</tr>
<? }?>
				<? // 지식 쇼핑 연동 설정 관련 추가
		if(false !== $naverep = checkNaverEp()){
			if(!_empty($naverep['shopping'])){ ?>
				<tr>
					<th><b>네이버 지식쇼핑 연동</b></th>
					<td>
						<input type="checkbox" name="syncNaverEp" value="0" <?=(($syncNaverEp=='0')?'checked':'')?> />
						선택 할경우 네이버 지식 쇼핑 연동시 해당 상품은 제외 합니다. </td>
				</tr>
				<? }
		}
		?>
			</tbody>
		</table>
		<input type=hidden  name="gonggu_product" value="N" />		
		
	</div>
	<input type=hidden id="idx_insertdate30" name=insertdate3 value="Y" onclick="DateFixAll(this)">
	<div style="text-align:center">
	<? if (strlen($prcode)==0) { ?>
		<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align=absmiddle width="144" height="38" border="0" vspace="5"></a>
	<? } else {?>
		<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align=absmiddle width="162" height="38" border="0" vspace="5"></B></a> &nbsp; <a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align=absmiddle width="113" height="38" border="0" vspace="5"></B></a>
	<? }?>
	</div>
	<div style="text-align:right">
		<? if (strlen($prcode)>0) { ?>
		<a href="JavaScript:NewPrdtInsert()"  onMouseOver="window.status='신규입력';return true;"><img src="images/product_newregicn.gif" align=absmiddle border="0" width="142" height="38" vspace="5"></a>
		<? } ?>
	</div>
<?	}	?>
		<input type=hidden name=iconnum value='<?=$totaliconnum?>'>
		<input type=hidden name=iconvalue>
		<input type=hidden name=optnum1 value=<?=$optnum1?>>
		<input type=hidden name=optnum2 value=<?=$optnum2?>>
</form>
<form name=cForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=popup value="<?=$popup?>">
	<input type=hidden name=code value=<?=$code?>>
	<input type=hidden name=prcode value=<?=$prcode?>>
	<input type=hidden name=delprdtimg>
	<input type=hidden name="vimage" value="<? if ($_data) echo $_data->maximage; ?>">
	<input type=hidden name="vimage2" value="<? if ($_data) echo $_data->minimage; ?>">
	<input type=hidden name="vimage3" value="<? if ($_data) echo $_data->tinyimage; ?>">
	<input type=hidden name="attechwide" value="<? if ($_data) echo $_data->wideimage; ?>">
</form>
<form name=icon action="product_iconmy.php" method=post target=icon>
</form>
<form name=iconlist action="product_iconlist.php" method=post target=iconlist>
</form>
<form name=vForm action="vender_infopop.php" method=post>
	<input type=hidden name=vender>
</form>
<?=$onload?>
<?
if (strlen($code)==12 && $predit_type=="Y") {
?>
<script language="Javascript1.2" src="htmlarea/editor.js"></script> 
<script language="JavaScript">
function htmlsetmode(mode,i){
	if(mode==document.form1.htmlmode.value) {
		return;
	} else {
		//i.checked=true;
		//editor_setmode('content',mode);
	}
	document.form1.htmlmode.value=mode;
}
//_editor_url = "htmlarea/";
//editor_generate('content');
</script>
<?
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--

// 배송수단 선택
$j(':checkbox[name="deli_type[]"]').click(function() {
	if ($j(':checkbox[name="deli_type[]"]:checked').length == 0) {
		alert('최소 하나의 배송 수단을 선택해야 합니다.\n[택배]를 자동선택합니다.');
		$j('#deli_parsel').attr('checked', true);
	}
});

// 상품별 회원할인율
function DiscountPrd(mode) {
	document.form1.mode.value=mode;
	document.form1.submit();
}



//상품별 회원할인율 자동계산
function autoCal(gubun,val,obj){
	if( document.form1.sellprice.value <= 0 ) {
		alert("상품가격을 먼저 입력하셔야 합니다.");
		document.form1.sellprice.focus();
		return false;
	}
	if(gubun=="1"){
		document.getElementById(obj).value = eval(document.form1.sellprice.value * (val/100)).toFixed(0);
	}else{
		document.getElementById(obj).value = eval((val/document.form1.sellprice.value) *100).toFixed(2);
	}
}


function CheckForm(mode) {

oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

<? if ($gongtype=="Y"){ ?>
	gongname="시작가";
	gongname2="공구가";
<? }else{ ?>
	 gongname="소비자가격";
	 gongname2="판매가격";
<? } ?>
	if (document.form1.productname.value.length==0) {
		alert("상품명을 입력하세요.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>300) {
		alert('총 입력가능한 길이가 한글 150자까지입니다. 다시한번 확인하시기 바랍니다.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		document.form1.consumerprice.value = 0;
		//alert(gongname+"을 입력하세요.");
		//document.form1.consumerprice.focus();
		//return;
	}
	
	<? if(_isInt($_data->pridx)){ ?>
	if(document.form1.goodsType.value=="2"){
	<? }else{ ?>
	if ($j('#goodsType2') && $j('#goodsType2').prop('checked')){ // 렌탈 선택
	<? } ?>
		/*
		if(document.form1.pricetype.value=="day"){
			if(document.form1.halfday[0].checked==false && document.form1.halfday[1].checked==false){
				alert("당일 12시간 대여허용여부를 선택하세요.");
				document.form1.halfday[0].focus();
				return;
			}
			if(document.form1.oneday_ex[0].checked==false && document.form1.oneday_ex[1].checked==false){
				alert("1일 초과시 과금기준을 선택하세요.");
				document.form1.oneday_ex[0].focus();
				return;
			}

			if(document.form1.halfday[0].checked==true && document.form1.halfday_percent.value==""){
				alert("당일 12시간 요금을 입력하세요.");
				document.form1.halfday_percent.focus();
				return;
			}
			if(document.form1.oneday_ex[1].checked==true && document.form1.time_percent.value==""){
				alert("추가 1시간 요금을 입력하세요.");
				document.form1.time_percent.focus();
				return;
			}

			if($('input[name^=nomalPrice]').val()==0 || $('input[name^=nomalPrice]').val()==""){
				alert("대여가격을 입력하세요.");
				$('input[name^=nomalPrice]').focus();
				return;
			}
		}
		
		if(document.form1.pricetype.value=="time"){
			if( document.form1.base_price.value=="" || document.form1.base_price.value==0){
				alert("기본 요금을 입력하세요.");
				document.form1.base_price.focus();
				return;
			}
			if( document.form1.timeover_price.value==""){
				alert("1시간당 추가 요금을 입력하세요.");
				document.form1.timeover_price.focus();
				return;
			}
		}

		if(document.form1.pricetype.value=="checkout"){
			if( document.form1.checkin_time.value=="" || document.form1.checkin_time.value==0){
				alert("체크인 시간을 입력하세요.");
				document.form1.checkin_time.focus();
				return;
			}
			if( document.form1.checkout_time.value=="" || document.form1.checkout_time.value==0){
				alert("체크아웃 시간을 입력하세요.");
				document.form1.checkout_time.focus();
				return;
			}
		}

		*/
	}else{ // 일반 판매 상품
		if (document.form1.sellprice.value == 0 && document.form1.goodsType1.checked == true) {
			alert("판매가가 0원 입니다. 판매가를 설정해 주시기 바랍니다.");
			document.form1.sellprice.focus();
			return;
		}

		if (isNaN(document.form1.consumerprice.value)) {
			alert(gongname+"을 숫자로만 입력하세요.(콤마제외)");
			document.form1.consumerprice.focus();
			return;
		}
		<? if($_data->vender<=0){?>
			if(document.form1.sellprice.disabled==false) {
				if (document.form1.sellprice.value.length==0) {
					alert(gongname2+"을 입력하세요.");
					document.form1.sellprice.focus();
					return;
				}
				if (isNaN(document.form1.sellprice.value)) {
					alert(gongname2+"을 숫자로만 입력하세요.(콤마제외)");
					document.form1.consumerprice.focus();
					return;
				}
			}
		<? }?>
		<? if ($gongtype=="N") {?>
			if (document.form1.checkquantity[2].checked==true) {
		<? } else {?>
			if (document.form1.checkquantity[1].checked==true) {
		<? }?>
				if (document.form1.quantity.value.length==0) {
					alert("수량을 입력하세요.");
					document.form1.quantity.focus();
					return;
				} else if (isNaN(document.form1.quantity.value)) {
					alert("수량을 숫자로만 입력하세요.");
					document.form1.quantity.focus();
					return;
				} else if (parseInt(document.form1.quantity.value)<=0) {
					alert("수량은 0개이상이여야 합니다.");
					document.form1.quantity.focus();
					return;
				}
			}
			miniq_obj=document.form1.miniq;
			maxq_obj=document.form1.maxq;
			if (miniq_obj.value.length>0) {
				if (isNaN(miniq_obj.value)) {
					alert ("최소주문한도는 숫자로만 입력해 주세요.");
					miniq_obj.focus();
					return;
				}
			}
			if (document.form1.checkmaxq[1].checked==true) {
				if (maxq_obj.value.length==0) {
					alert ("최대주문한도의 수량을 입력해 주세요.");
					maxq_obj.focus();
					return;
				} else if (isNaN(maxq_obj.value)) {
					alert ("최대주문한도의 수량을 숫자로만 입력해 주세요.");
					maxq_obj.focus();
					return;
				}
			}
			if (miniq_obj.value.length>0 && document.form1.checkmaxq[1].checked==true && maxq_obj.value.length>0) {
				if (parseInt(miniq_obj.value) > parseInt(maxq_obj.value)) {
					alert ("최소주문한도는 최대주문한도 보다 작아야 합니다.");
					miniq_obj.focus();
					return;
				}
			}
	}
	
	/*
	if (document.form1.reserve.value.length>0) {
		if(document.form1.reservetype.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("적립률은 숫자와 특수문자 소수점\(.\)으로만 입력하세요.");
				document.form1.reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.reserve.value,".")>2) {
				alert("적립률 소수점\(.\)은 한번만 사용가능합니다.");
				document.form1.reserve.focus();
				return;
			}

			if(getPointCount(document.form1.reserve.value,".",2)==true) {
				alert("적립률은 소수점 이하 둘째자리까지만 입력 가능합니다.");
				document.form1.reserve.focus();
				return;
			}

			if(Number(document.form1.reserve.value)>100 || Number(document.form1.reserve.value)<0) {
				alert("적립률은 0 보다 크고 100 보다 작은 수를 입력해 주세요.");
				document.form1.reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.reserve.value,"")) {
				alert("적립금은 숫자로만 입력하세요.");
				document.form1.reserve.focus();
				return;
			}
		}
	}
	*/


	if(document.form1.deli[3].checked==true || document.form1.deli[4].checked==true) {
		if(document.form1.deli[3].checked==true)
		{
			if (document.form1.deli_price_value1.value.length==0) {
				alert("개별배송비를 입력하세요.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value1.value)) {
				alert("개별배송비는 숫자로만 입력하세요.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value1.value)<=0) {
				alert("개별배송비는 0원 이상 입력하셔야 합니다.");
				document.form1.deli_price_value1.focus();
				return;
			}
		}
		else
		{
			if (document.form1.deli_price_value2.value.length==0) {
				alert("개별배송비를 입력하세요.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value2.value)) {
				alert("개별배송비는 숫자로만 입력하세요.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value2.value)<=0) {
				alert("개별배송비는 0원 이상 입력하셔야 합니다.");
				document.form1.deli_price_value2.focus();
				return;
			}
		}
	}

	searchtype=false;
	for(i=0;i<document.form1.searchtype.length;i++) {
		if(document.form1.searchtype[i].checked==true) {
			searchtype=true;
			shop="layer"+i;
			break;
		}
	}

	if(searchtype==false) {
		alert("옵션 타입을 선택하세요.\n\n공동구매 타입의 상품일 경우 옵션그룹 사용이 불가하오니\n잘 확인하시기 바랍니다.");
		document.form1.searchtype[0].focus();
		return;
	}

	if(document.form1.sellprice.disabled==false) {
		if(shop=="layer0") {

		} else if(shop=="layer1"){
			optnum1=0;
			optnum2=0;

			//옵션1 항목
			document.form1.option1.value="";
			for(i=0;i<10;i++){
				if(document.form1.optname1[i].value.length>0) {
					document.form1.option1.value+=document.form1.optname1[i].value+",";
					optnum1++;
				}
			}

			//옵션1 제목 검사 (옵션1 항목이 NULL이 아니면)
			if((document.form1.option1.value.length!=0 && document.form1.option1_name.value.length==0)
			|| (document.form1.option1.value.length==0 && document.form1.option1_name.value.length!=0)){
				alert('각 옵션별 조건입력과 [옵션제목]을 확인해주세요!');
				if(document.form1.option1_name.value.length==0) {
					document.form1.option1_name.focus();
				} else {
					document.form1.optname1[0].focus();
				}
				return;
			}

			//옵션2 항목
			document.form1.option2.value="";
			for(i=0;i<10;i++){
				if(document.form1.optname2[i].value.length>0) {
					document.form1.option2.value+=document.form1.optname2[i].value+",";
					 optnum2++;
				}
			}

			//옵션2 제목 검사 (옵션2 항목이 NULL이 아니면)
			if((document.form1.option2.value.length!=0 && document.form1.option2_name.value.length==0)
			|| (document.form1.option2.value.length==0 && document.form1.option2_name.value.length!=0)){
				alert('각 옵션별 조건입력과 [옵션제목]을 확인해주세요!');
				if(document.form1.option2_name.value.length==0) {
					document.form1.option2_name.focus();
				} else {
					document.form1.optname2[0].focus();
				}
				return;
			}

			//옵션2만 입력했는지 검사
			if(document.form1.option1.value.length==0 && document.form1.option2.value.length>0) {
				alert('옵션2는 옵션1 입력후 입력가능합니다.');
				document.form1.option1_name.focus();
				return;
			}

			//옵션1에 따른 가격 검사
			document.form1.option_price.value="";
			pricecnt=0;
			for(i=0;i<optnum1;i++){
				if(document.form1.optprice[i].value.length==0){
					pricecnt++;
				}else{
					document.form1.option_price.value+=document.form1.optprice[i].value+",";
				}
			}
			if(optnum1>0 && pricecnt!=0 && pricecnt!=optnum1){
				alert('옵션별 가격은 모두 입력하거나 모두 입력하지 않아야 합니다.');
				document.form1.optprice[0].focus();
				return;
			}

			if(document.form1.option_price.value.length!=0) temp=0;
			else temp=-1;
			temp2=document.form1.option_price.value;
			while(temp!=-1){
				temp=temp2.indexOf(",");
				if(temp!=-1) temp3=(temp2.substring(0,temp));
				else temp3=temp2;
				if(isNaN(temp3)){
					alert("옵션 가격은 숫자만 입력을 하셔야 합니다.");
					document.form1.option_price.focus();
					return;
				}
				temp2=temp2.substring(temp+1);
			}

			//재고수량 및 숫자검사
			isquan=false;
			quanobj="";
			for(i=0;i<10;i++) {
				isgbn1=false;
				if(i<optnum1) isgbn1=true;

				for(j=0;j<10;j++) {
					isgbn2=false;
					if(optnum2>0) {
						if(j<optnum2 && isgbn1==true) isgbn2=true;
					} else {
						if(j==0 && isgbn1==true) isgbn2=true;
					}

					if(isgbn2==true) {
						if(isquan==false && document.form1["optnumvalue["+j+"]["+i+"]"].value.length==0) {
							isquan=true;
							quanobj=document.form1["optnumvalue["+j+"]["+i+"]"];
						}
					} else {
						if(document.form1["optnumvalue["+j+"]["+i+"]"].value.length>0) {
							alert("입력하신 수량이 옵션정보의 범위를 넘었습니다. ("+(i+1)+" 째줄 "+(j+1)+" 째칸)");
							document.form1["optnumvalue["+j+"]["+i+"]"]. focus();
							return;
						}
					}
				}
			}
			if(isquan==true) {
				if(!confirm("수량 입력이 안된 옵션정보는 무제한 수량으로 등록됩니다.\n\n계속 하시겠습니까?")) {
					quanobj.focus();
					return;
				}
			}

		} else if(shop=="layer2"){
			if (document.form1.toption_price.value.length!=0 && document.form1.toption1.value.length==0) {
				alert("특수코드별가격을 입력하면 반드시 특수코드입력1에도 내용을 입력해야 합니다.");
				document.form1.toption1.focus();
				return;
			}
			if(document.form1.toption_price.value.length!=0) temp=0;
			else temp=-1;
			temp2=document.form1.toption_price.value;
			while(temp!=-1){
				temp=temp2.indexOf(",");
				if(temp!=-1) temp3=(temp2.substring(0,temp));
				else temp3=temp2;
				temp4=" "+temp3;
				if(isNaN(temp3) || temp4.indexOf('.')>0){
					alert("옵션 가격은 숫자만 입력을 하셔야 합니다.");
					document.form1.toption_price.focus();
					return;
				}
				temp2=temp2.substring(temp+1);
			}
			document.form1.option_price.value=document.form1.toption_price.value+",";
			document.form1.option1_name.value=document.form1.toptname1.value;
			document.form1.option1.value=document.form1.toption1.value+",";
			document.form1.option2_name.value=document.form1.toptname2.value;
			document.form1.option2.value=document.form1.toption2.value+",";
<?	if($gongtype=="N" && (int)$_data->vender==0){?>
		} else if(shop=="layer3") {
			if(document.form1.optiongroup.selectedIndex==0) {
				alert("옵션그룹을 선택하세요.");
				document.form1.optiongroup.focus();
				return;
			}
<? } ?>
		}
	}
<?
	if($sns_ok =="Y" && $arSnsType[0] =="B"){
?>
	if (document.form1.sns_reserve1.value.length>0) {
		if(document.form1.sns_reserve1_type.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("적립률은 숫자와 특수문자 소수점\(.\)으로만 입력하세요.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve1.value,".")>2) {
				alert("적립률 소수점\(.\)은 한번만 사용가능합니다.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve1.value,".",2)==true) {
				alert("적립률은 소수점 이하 둘째자리까지만 입력 가능합니다.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(Number(document.form1.sns_reserve1.value)>100 || Number(document.form1.sns_reserve1.value)<0) {
				alert("적립률은 0 보다 크고 100 보다 작은 수를 입력해 주세요.");
				document.form1.sns_reserve1.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve1.value,"")) {
				alert("적립금은 숫자로만 입력하세요.");
				document.form1.sns_reserve1.focus();
				return;
			}
		}
	}
	if (document.form1.sns_reserve2.value.length>0) {
		if(document.form1.sns_reserve2_type.value=="Y") {
			if(isDigitSpecial(document.form1.sns_reserve2.value,".")) {
				alert("적립률은 숫자와 특수문자 소수점\(.\)으로만 입력하세요.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve2.value,".")>2) {
				alert("적립률 소수점\(.\)은 한번만 사용가능합니다.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve2.value,".",2)==true) {
				alert("적립률은 소수점 이하 둘째자리까지만 입력 가능합니다.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(Number(document.form1.sns_reserve2.value)>100 || Number(document.form1.sns_reserve2.value)<0) {
				alert("적립률은 0 보다 크고 100 보다 작은 수를 입력해 주세요.");
				document.form1.sns_reserve2.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve2.value,"")) {
				alert("적립금은 숫자로만 입력하세요.");
				document.form1.sns_reserve2.focus();
				return;
			}
		}
	}
<?
}
if($arRecomType[0] =="B" && $arRecomType[1] == "B"){
?>

	if (document.form1.first_reserve.value.length>0) {
		if(document.form1.first_reserve_type.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("적립률은 숫자와 특수문자 소수점\(.\)으로만 입력하세요.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.first_reserve.value,".")>2) {
				alert("적립률 소수점\(.\)은 한번만 사용가능합니다.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getPointCount(document.form1.first_reserve.value,".",2)==true) {
				alert("적립률은 소수점 이하 둘째자리까지만 입력 가능합니다.");
				document.form1.first_reserve.focus();
				return;
			}

			if(Number(document.form1.first_reserve.value)>100 || Number(document.form1.first_reserve.value)<0) {
				alert("적립률은 0 보다 크고 100 보다 작은 수를 입력해 주세요.");
				document.form1.first_reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.first_reserve.value,"")) {
				alert("적립금은 숫자로만 입력하세요.");
				document.form1.first_reserve.focus();
				return;
			}
		}
	}
<?
}
?>
	if(document.form1.use_imgurl && document.form1.use_imgurl.checked!=true) {
		filesize = Number(document.form1.size_checker.fileSize) + Number(document.form1.size_checker2.fileSize) + Number(document.form1.size_checker3.fileSize) ;
		if(filesize><?=$maxfilesize?>) {
			alert('올리시려고 하는 파일용량이 500K이상입니다.\n파일용량을 체크하신후에 다시 이미지를 올려주세요');
			return;
		}
	}
	tempcontent = document.form1.content.value;
	/*
<?if ($predit_type=="Y"){ ?>
	if(mode=="modify" && tempcontent.length>0 && tempcontent.indexOf("<")==-1 && tempcontent.indexOf(">")==-1 && !confirm("웹편집기 기능추가로 텍스트로만 입력하신 상세설명은\n줄바꾸기가 해제되어 쇼핑몰에서 다르게 보여질 수 있습니다.\n\n재입력하시거나 현재 쇼핑몰에서 해당 상품의 상세설명을\n그대로 마우스로 드래그하여 붙여넣기를 해서 재입력하셔야 합니다.\n\n위와 같이 수정하지 않고 저장하시려면 [확인]을 누르세요.")){
		return;
	}
<?}?>*/
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}
//	if (document.form1.insertdate1.checked==true) document.form1.insertdate.value="Y";
	document.form1.insertdate.value="Y";
	document.form1.mode.value=mode;
	document.form1.submit();
}

<?
if($popup=="YES"){
	echo "
		window.moveTo(10,10);
		window.resizeTo(1200,700);
	";
} else {
	echo "
		parent.fResize();
	";
}
?>

<? /* 수수료 관련 jdy	*/?>
function commissionDivView(v) {

	cm_div = document.getElementById('commission_div');

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
		}else{
			cm_div.style.display="none";
		}
	}
}

function commissionOk(str) {

	if (str =='Y') {
		a_val = "요청 수수료를 승인 하시겠습니까?";
	}else{
		a_val = "요청 수수료를 거부 하시겠습니까?";
	}

	if(confirm(a_val)) {
		document.form1.mode.value="comm_ok";
		document.form1.commission_result.value=str;
		document.form1.submit();
	}

}

function commissionChange(str) {

	if (document.form1.up_rq_cost) {
		if (document.form1.up_rq_cost.value.length==0) {
			alert("상품공급가를 입력해주세요.");
			document.form1.up_rq_cost.focus();
			return;
		}

		if(isDigitSpecial(document.form1.up_rq_cost.value,"")) {
			alert("상품공급가는 숫자로만 입력하세요.");
			document.form1.up_rq_cost.focus();
			return;
		}
	}


	if (document.form1.up_rq_com) {
		if (document.form1.up_rq_com.value.length==0) {
			alert("수수료를 입력해주세요.");
			document.form1.up_rq_com.focus();
			return;
		}

		if(isDigitSpecial(document.form1.up_rq_com.value,"")) {
			alert("수수료는 숫자로만 입력하세요.");
			document.form1.up_rq_com.focus();
			return;
		}
	}

	if(confirm("수수료를 변경 하시겠습니까?")) {
		document.form1.mode.value="comm_admin";
		document.form1.submit();
	}

}



<? /* 수수료 관련 jdy	*/?>

//-->
</SCRIPT>
<?
if($searchtype==2 || $optionover=="YES") {
	echo "<script>document.form1.searchtype[2].checked=true;\nViewLayer('layer2');</script>";
} else if($searchtype==1) {
	echo "<script>document.form1.searchtype[1].checked=true;\nViewLayer('layer1');</script>";
} else if($searchtype==3 && $gongtype=="N" && (int)$_data->vender==0) {
	echo "<script>document.form1.searchtype[3].checked=true;\nViewLayer('layer3');</script>";
}
if ($_data->sns_state=="Y") {
	echo "<script>ViewSnsLayer('block');</script>";
}

//################################추가 카테고리관련 ###############
$sql = "SELECT * FROM tblproductcode ";
if(strlen($_ShopInfo->getMemid())==0) {
	$sql.= "WHERE group_code='' ";
} else {
	//$sql.= "WHERE (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.= "WHERE (group_code='' OR group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
}
// $sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
$sql.= " ORDER BY sequence DESC ";

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
<script type="text/javascript">
<!--
	parent.loadingIMG.style.display='none';
//-->
</script>
</body></html>
