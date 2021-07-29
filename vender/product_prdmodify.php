<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/admin_more.php");

if(substr($_venderdata->grant_product,1,1)!="Y") {
	echo "<html></head><body onload=\"alert('상품정보 수정 권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');\"></body></html>";exit;
}

$userspec_cnt = 5;
$maxfilesize="2097152";
$savewideimage = $Dir.DataDir."shopimages/wideimage/"; //와이드이미지
$mode=$_POST["mode"];
$prcode=$_POST["prcode"];
$code=substr($prcode,0,12);

// 정산 기준, 적립금, 쿠폰 사용여부 조회 jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];
$reserve_use = $shop_more_info['reserve_use'];
$coupon_use = $shop_more_info['coupon_use'];


$vender_more = getVenderMoreInfo($_VenderInfo->getVidx());
$commission_type = $vender_more['commission_type'];

if ($coupon_use ==1) {
	$coupon_use = $vender_more['coupon_use'];
}
if ($reserve_use ==1) {
	$reserve_use = $vender_more['reserve_use'];
}

// 배송수단 선택
$deli_type = $_POST['deli_type'];
if (is_array($deli_type)) {
	$deli_type = implode(',', $deli_type);
}

// 정산 기준, 적립금, 쿠폰 사용여부 조회 jdy

/*
$sql = "SELECT * FROM tblproduct WHERE productcode = '".$prcode."' AND vender='".$_VenderInfo->getVidx()."' ";
*/
/****** 수수료 관련 수정 jdy ************/
//$sql = "SELECT p.*, c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval FROM tblproduct p left join product_commission c on p.productcode=c.productcode WHERE p.productcode = '".$prcode."' AND vender='".$_VenderInfo->getVidx()."' ";

$sql = "SELECT p.*, c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval FROM tblproduct p left join product_commission c on p.productcode=c.productcode left join rent_product rp on rp.pridx=p.pridx WHERE p.productcode = '".$prcode."' AND (vender='".$_VenderInfo->getVidx()."' OR trust_vender='".$_VenderInfo->getVidx()."')";
/****** 수수료 관련 수정 jdy ************/

$result = mysql_query($sql,get_db_conn());
if (!$_data = mysql_fetch_object($result)) {
	echo "<html></head><body onload=\"alert('해당 상품이 존재하지 않습니다.');";
	if(strlen($mode)>0) {
		echo "location='product_myprd.php';";
	}
	echo "\"></body></html>";exit;
}
mysql_free_result($result);

$maxsize=130;
$makesize=130;
$sql = "SELECT predit_type,etctype FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$predit_type=$row->predit_type;
	if(strpos(" ".$row->etctype,"IMGSERO=Y")) {
		$imgsero="Y";
	}
}
mysql_free_result($result);


//POST 데이터 변수 세팅
$prmsg=$_POST["prmsg"];

$productname=$_POST["productname"];
$vimage=$_POST["vimage"];
$vimage2=$_POST["vimage2"];
$vimage3=$_POST["vimage3"];
$attechwide=!_empty($_POST['attechwide'])?trim($_POST['attechwide']):"";
$option1=$_POST["option1"];
$option1_name=$_POST["option1_name"];
$option2=$_POST["option2"];
$option2_name=$_POST["option2_name"];
$consumerprice=$_POST["consumerprice"];
$discountRate=$_POST["discountRate"];
$buyprice=$_POST["buyprice"];
$sellprice=$_POST["sellprice"];
$production=$_POST["production"];
$keyword=$_POST["keyword"];
$quantity=$_POST["quantity"];
$checkquantity=$_POST["checkquantity"];
$reserve=$_POST["reserve"];
$reservetype=$_POST["reservetype"];
$deli=$_POST["deli"];
if($deli=="Y")
	$deli_price=(int)$_POST["deli_price_value2"];
else
	$deli_price=(int)$_POST["deli_price_value1"];

if($deli=="H" || $deli=="F" || $deli=="G") $deli_price=0;
if($deli!="Y" && $deli!="F" && $deli!="G") $deli="N";
$display=$_POST["display"];
$addcode=$_POST["addcode"];
$option_price=ereg_replace(" ","",$_POST["option_price"]);
$option_price=substr($option_price,0,-1);
$madein=$_POST["madein"];
$model=$_POST["model"];
$brandname=$_POST["brandname"];
$opendate=$_POST["opendate"];
$selfcode=$_POST["selfcode"];
$optiongroup=$_POST["optiongroup"];
$imgcheck=$_POST["imgcheck"];
$deliinfono=$_POST["deliinfono"];	// 배송/교환/환불정보 노출안함 (Y)
$miniq=$_POST["miniq"];			// 최소주문가능
$maxq=$_POST["maxq"];			// 최대주문가능
$insertdate=$_POST["insertdate"];
$content=$_POST["content"];

$userspec=$_POST["userspec"];
$specname=$_POST["specname"];
$specvalue=$_POST["specvalue"];

$group_check=$_POST["group_check"];
$group_code=$_POST["group_code"];

/* 관리자폼과 동일하게 추가 jdy */

$etcapply_coupon=$_POST["etcapply_coupon"];
$etcapply_reserve=$_POST["etcapply_reserve"];
$etcapply_gift=$_POST["etcapply_gift"];
$etcapply_return=$_POST["etcapply_return"];
if($etcapply_coupon!="Y") $etcapply_coupon="N";
if($etcapply_reserve!="Y") $etcapply_reserve="N";
if($etcapply_gift!="Y") $etcapply_gift="N";
if($etcapply_return!="Y") $etcapply_return="N";

$bankonly=$_POST["bankonly"];


$productdisprice=$_POST["productdisprice"];
$dicker=$_POST["dicker"];
$dicker_text=$_POST["dicker_text"];

$trust_vender=$_POST["trust_vender"];
$maincommi=$_POST["maincommi"];
$booking_confirm=$_POST["booking_confirm"]=="now"?$_POST["booking_confirm"]:$_POST["booking_confirm_time"];


//예약 판매 상품 정보
$reservation = ( $_POST["reservation"] == "Y" AND strlen($_POST["reservationDate"]) > 0 ) ? $_POST["reservationDate"] : '' ;

//당일예약여부
$today_reserve=!_empty($_POST['today_reserve'])?trim($_POST['today_reserve']):"N";

/* 관리자폼과 동일하게 추가 jdy */

if($group_check=="Y" && count($group_code)>0) {
	$group_check="Y";
} else {
	$group_check="N";
	$group_code="";
}

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

$userfile = $_FILES["userfile"];
$userfile2 = $_FILES["userfile2"];
$userfile3 = $_FILES["userfile3"];

$delprdtimg=$_POST["delprdtimg"];


if(strlen($_POST["setcolor"])==0){
	$setcolor=$_COOKIE["setcolor"];
} else if($_COOKIE["setcolor"]!=$_POST["setcolor"]){
	SetCookie("setcolor",$setcolor,0,"/".RootPath.VenderDir);
	$setcolor=$_POST["setcolor"];
} else {
	$setcolor=$_COOKIE["setcolor"];
}

if(strlen($setcolor)==0) $setcolor="000000";
$rcolor=HexDec(substr($setcolor,0,2));
$gcolor=HexDec(substr($setcolor,2,2));
$bcolor=HexDec(substr($setcolor,4,2));
$quality = "90";


$goodsType = $_POST["goodsType"];

// 렌탈 옵션 처리
$productoptions = array();
if($goodsType == '2'){
	$optquantity =0;
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
		//$quantity = $productoptions[0]['productCount'];
		$quantity = $optquantity;
	}
	
}



// 테두리 설정에 대한 부분을 쿠키로 고정시킨다.
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	SetCookie("imgborder","Y",0,"/".RootPath.VenderDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && $mode=="update") {
	SetCookie("imgborder","",time()-3600,"/".RootPath.VenderDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}

// 상품등록일자 고정에 대한 쿠키를 검사하고 노릇하게 구워놓는다..
if ($_COOKIE["insertdate_cook"]=="Y" && $insertdate!="Y" && $mode=="update") {
	setCookie("insertdate_cook","",time()-3600,"/".RootPath.VenderDir);
	$insertdate_cook="";
} else if ($_COOKIE["insertdate_cook"]!="Y" && $insertdate=="Y" && $mode=="update") {
	setCookie("insertdate_cook","Y",time()+2592000,"/".RootPath.VenderDir);
	$insertdate_cook="Y";
}
// 쿠키 끝

$imagepath=$Dir.DataDir."shopimages/product/";

if($mode=="delprdtimg") {
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
	echo "<html></head><body onload=\"alert('해당 상품이미지를 삭제하였습니다.');parent.document.iForm.submit();\"></body></html>"; exit;
} else if($mode=="update") {
	$image_name = $prcode;

	$etctype = "";
	if ($bankonly=="Y") $etctype .= "BANKONLY";
	if ($deliinfono=="Y") $etctype .= "DELIINFONO=Y";
	if ($setquota=="Y") $etctype .= "SETQUOTA";
	if (strlen(substr($iconvalue,0,3))>0)       $etctype .= "ICON=".$iconvalue."";
	if ($dicker=="Y" && strlen($dicker_text)>0) $etctype .= "DICKER=".$dicker_text."";

	if ($miniq>1)       $etctype .= "MINIQ=".$miniq."";
	else if ($miniq<1){
		echo "<html></head><body onload=\"alert('최소주문한도 수량은 1개 보다 커야 합니다.')\"></body></html>";exit;
	}
	if ($checkmaxq=="B" && $maxq>=1)        $etctype .= "MAXQ=".$maxq."";
	else if ($checkmaxq=="B" && $maxq<1) {
		echo "<html></head><body onload=\"alert('최대주문한도 수량은 1개 보다 커야 합니다.')\"></body></html>";exit;
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

	$file_size = $userfile[size]+$userfile2[size]+$userfile3[size];

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
		$addcode = ereg_replace("\\\\'","''",$addcode);
		$prmsg = ereg_replace("\\\\'","''",$prmsg);

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$_data->content,$edtimg)){
			if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$content,$edimg)) $edimg[1] =array();
			foreach($edtimg[1] as $cimg){
				if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
			}
		}

		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$content = str_replace('/data/editor_temp/','/data/editor/',$content);
		}
		/** #에디터 관련 파일 처리 추가 부분 */


		$content = ereg_replace("\\\\'","''",$content);

		$message="";

		if($imgcheck=="Y") $filename = array (&$userfile[name],&$userfile[name],&$userfile[name]);
		else $filename = array (&$userfile[name],&$userfile2[name],&$userfile3[name]);
		$file = array (&$userfile[tmp_name],&$userfile2[tmp_name],&$userfile3[tmp_name]);
		$vimagear = array (&$vimage,&$vimage2,&$vimage3);
		$imgnum = array ("","2","3");
		
		for($i=0;$i<3;$i++){
			if ($mode=="update" && strlen($vimagear[$i])>0 && strlen($filename[$i])>0 && file_exists($imagepath.$vimagear[$i])) {
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
				$image[$i] =$image_name.$imgnum[$i].".".$ext;
				copy($imagepath.$image[0],$imagepath.$image[$i]);
			} else {
				$image[$i] = $vimagear[$i];
			}
		}
		
		if ($imgcheck=="Y" && strlen($filename[1])>0 && file_exists($imagepath.$image[1])) {
			$imgname=$imagepath.$image[1];
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize1=300;
			if ($width>$makesize1 || $height>$makesize1) {
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
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
			$makesize2=200;
			$changefile="Y";
			if($imgsero=="Y") $leftmax=$makesize2;
			else $leftmax=$maxsize;
			if ($width>$maxsize || $height>$leftmax) {
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
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
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
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
		if($optiongroup>0) {
			$option1="[OPTG".$optiongroup."]";
			$option2="";
			$option_price="";
		}		

		#와이드 이미지 추가
		if(!_empty($_FILES['wideimage']['name'])){
			$wmaxfilesize="2097152";
			$attechfilename=$widefilename="";
			$tempext=$widefileext=array();
			$widesaveloc = $_SERVER['DOCUMENT_ROOT']."/data/shopimages/wideimage/";
			$allowimagefile = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png','image/gif');
			$tempext = pathinfo($_FILES['wideimage']['name']);
			$widefileext = strtolower($tempext['extension']);
			$widefilename = $prcode.".".$widefileext;
			if(is_file($savewideimage.$widefilename)){
				@unlink($savewideimage.$widefilename);
			}

			if(!is_dir($widesaveloc)){
				if(mkdir($widesaveloc)){
					@chmod($widesaveloc, 0707);
				}
			}

			if(in_array($_FILES['wideimage']['type'],$allowimagefile)){
				if($_FILES['wideimage']['size']<=$wmaxfilesize){
					if(move_uploaded_file($_FILES['wideimage']['tmp_name'],$savewideimage.$widefilename)){
						$attechfilename = $widefilename;
					}
				}
			}
		}

		$sql = "SELECT sellprice,assembleproduct FROM tblproduct ";
		$sql.= "WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		$vsellprice=$row->sellprice;
		$vassembleproduct=$row->assembleproduct;

		if(strlen($buyprice) < 1 ) $buyprice = 0 ;

		$sql = "UPDATE tblproduct SET ";
		$sql.= "productname		= '".$productname."', ";
		$sql.= "prmsg		= '".$prmsg."', ";
		$sql.= "assembleuse		= 'N', ";
		$sql.= "assembleproduct	= '', ";
		$sql.= "sellprice		= ".$sellprice.", ";
		$sql.= "consumerprice	= ".$consumerprice.", ";
		$sql.= "discountRate	= ".$discountRate.", ";
		$sql.= "buyprice		= ".$buyprice.", ";
		$sql.= "reserve			= '".$reserve."', ";
		$sql.= "reservetype		= '".$reservetype."', ";
		$sql.= "production		= '".$production."', ";
		$sql.= "madein			= '".$madein."', ";
		$sql.= "model			= '".$model."', ";
		$sql.= "opendate		= '".$opendate."', ";
		$sql.= "selfcode		= '".$selfcode."', ";
		$sql.= "quantity		= ".$quantity.", ";
		$sql.= "group_check		= '".$group_check."', ";
		$sql.= "keyword			= '".$keyword."', ";
		$sql.= "addcode			= '".$addcode."', ";
		$sql.= "userspec		= '".$userspec."', ";
		$sql.= "maximage		= '".$image[0]."', ";
		$sql.= "minimage		= '".$image[1]."', ";
		$sql.= "tinyimage		= '".$image[2]."', ";
		if($searchtype!=0) {
			$sql.= "option_price	= '".$option_price."', ";
			$sql.= "option_quantity	= '".$optcnt."', ";
			$sql.= "option1			= '".$option1."', ";
			$sql.= "option2			= '".$option2."', ";
		} else {
			$sql.= "option_price	= '', ";
			$sql.= "option_quantity	= '', ";
			$sql.= "option1			= '', ";
			$sql.= "option2			= '', ";
		}
		$sql.= "etctype			= '".$etctype."', ";
		$sql.= "deli_type		= '".$deli_type."', ";
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		if(substr($_venderdata->grant_product,3,1)=="N") {

			//수수료 승인이 나지 않은 상태에서 노출 불가 jdy
			if ($account_rule=="1" || $commission_type=="1") {
				$p_sql = "select first_approval from product_commission where productcode='".$prcode."'" ;
				$result=mysql_query($p_sql,get_db_conn());
				$data=mysql_fetch_array($result);
				
				if($data[0] != "1") $sql.= "display		= 'N', ";
			}
		}else{
			$display="N";			
		}
		
		if($_REQUEST['istrust'] == '-1') $display = 'N';
		
		$sql.= "display			= '".$display."', ";
		
		if($insertdate!="Y") {
			$sql.= "date			= '".$curdate."', ";
		}
		
		$sql.= "reservation		= '".$reservation."', ";

		$sql.= "today_reserve	= '".$today_reserve."', "; //당일예약추가

		/* 관리자폼과 동일하게 추가 jdy */

		$sql.= "etcapply_coupon	= '".$etcapply_coupon."', ";
		$sql.= "etcapply_reserve= '".$etcapply_reserve."', ";
		$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
		$sql.= "etcapply_return	= '".$etcapply_return."', ";

		$sql.= "productdisprice	= '".$productdisprice."', ";
		/* 관리자폼과 동일하게 추가 jdy */
		
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
		//검색 키워드등록 end

		$sql.= "booking_confirm	= '".$booking_confirm."', ";

		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".$content."', ";

		$sql.= "rental = '".$goodsType."' ";
//		$sql.= ",reseller_reserve = '".$reseller_reserve."' "; //추가
		// 적립금 변경 요청이 있을 경우			
		
		$reseller_reserve = ''; // 변경 요청 이 있는 경우 값을 넣어서 변경 요청 기록 처리

		if($_REQUEST['reseller_reserve'] != '-1'){			
			if(_empty($_REQUEST['reseller_reserve'])) $sql.= ",reseller_reserve = '-1' "; //추가			
			if($_POST['reseller_reserve'] == '0') $reseller_reserve = 0;
			else if(_isInt($_POST['reseller_reserve'])) $reseller_reserve = floatval($_POST['reseller_reserve']/100);		
		}
		
		
		$sql.= "WHERE productcode = '".$prcode."' ";
		
		if(mysql_query($sql,get_db_conn())) {
			$pridx = productcodeToPridx($prcode);
			
			if(!_empty($reseller_reserve)){ //
				$sql = "insert into req_chgresellerreserv (productcode,reseller_reserve) values ('".$prcode."','".$reseller_reserve."') ON DUPLICATE KEY UPDATE reseller_reserve=values(reseller_reserve)";
				mysql_query($sql,get_db_conn());
			}
			
			// 렌탈 옵션 처리
			if($goodsType == '2'){				
				// 대여 상품 저장
				$rentProductValue = array();
				$rentProductValue['pridx'] = $pridx;
				$rentProductValue['istrust'] = $_POST["istrust"];
				$rentProductValue['location'] = $_POST["location"];
				$rentProductValue['goodsType'] = $_POST["goodsType"];
				$rentProductValue['itemType'] = $_POST["itemType"];			
				$rentProductValue['multiOpt'] = ($_REQUEST['multiOpt'] == '1')?'1':'0';
				if($rentProductValue['multiOpt'] == '0') $rentProductValue['tgrade'] = $productoptions[0]['grade'];
				
				$rentProductValue['maincommi'] = $_POST["maincommi"];	
				$rentProductValue['trust_vender'] = $_POST["trust_vender"];	
				$rentProductValue['trust_approve'] = $_POST["trust_approve"];

				$codeA=substr($prcode,0,3);

				//변경전 수수료값 가져오기
				$rentPr = rentProduct($pridx);

				if($_POST["trust_vender"]!=$rentPr["trust_vender"]){
					
					$rentProductValue['maincommi'] = "0";
					//위탁업체 수수료가져오기
					$sql = "SELECT ta.ta_idx,tm.product_commi FROM tbltrustagree ta ";
					$sql.= "left join tbltrustmanage tm on tm.vender=ta.take_vender ";
					$sql.= "WHERE (ta.take_vender='".$_VenderInfo->getVidx()."' OR ta.give_vender='".$_VenderInfo->getVidx()."') ";
					$sql.= "AND (ta.take_vender='".$_POST["trust_vender"]."' OR ta.give_vender='".$_POST["trust_vender"]."') ";
					$sql.= "AND (ta.approve='Y' OR ta.approve='N')";
					$res=mysql_query($sql,get_db_conn());
					$rw=mysql_fetch_object($res);

					$arrPr_commi = explode("//",$rw->product_commi);
					for($i=0;$i<sizeof($arrPr_commi);$i++){
						$arrCommi[$i] = explode(":",$arrPr_commi[$i]);
						
						if($codeA==$arrCommi[$i][0]){
							$rentProductValue['maincommi'] = $arrCommi[$i][1];
						}
					}
					
					if($_POST["istrust"]=="0" && $rentProductValue['maincommi'] == "0"){
						$onload="<html></head><body onload=\"alert('위탁신청한 상품의 카테고리와 위탁 카테고리가 일치하지 않습니다.')\"></body></html>";
						echo $onload;exit;
					}else{
						$rentProductResult = rentProductSave( $rentProductValue );
						rentProduct::updateOptions($pridx,$productoptions);	
					}
				
				}else{
					
					$rentProductResult = rentProductSave( $rentProductValue );
					rentProduct::updateOptions($pridx,$productoptions);
				
					//위탁수수료변경이 있는 경우
					if($_POST["maincommi"]!=$rentPr["maincommi"]){
						$sql2 = "insert into tbltrustcommission values ('".$prcode."','".$_POST["vender"]."','".$_POST["trust_vender"]."','".$_VenderInfo->getVidx()."','1',now())";
						mysql_query($sql2,get_db_conn());					
					}
				}

			}
			
			
			
			// 멀티 카테고리 추가후 오류 관련 수정
			$sql = "SELECT count(*) FROM tblcategorycode WHERE productcode = '".$prcode."' ";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_result($res,0,0) < 1){
					$sql = "insert into tblcategorycode set productcode='".$prcode."',categorycode='".substr($prcode,0,12)."'";
					@mysql_query($sql,get_db_conn());
				}
			}

			// 상품 정보 고시 관련 추가
			$sql = "select pridx from tblproduct WHERE productcode = '".$prcode."'  limit 1";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				$pridx = mysql_result($res,0,0);
				$ditems = array();
				foreach($_REQUEST['didx'] as $k=>$v){
					$item = array();
					$item['didx'] = $v;
					$item['dtitle'] = $_REQUEST['dtitle'][$k];
					$item['dcontent'] = $_REQUEST['dcontent'][$k];
					array_push($ditems,$item);
				}
				_editProductDetails($pridx,$ditems);
			}
			// #상품 정보 고시 관련 추가

			if(strlen($brandname)>0) { // 브랜드 관련 처리
				$result = mysql_query("SELECT bridx FROM tblproductbrand WHERE brandname = '".$brandname."' ",get_db_conn());
				if ($row=mysql_fetch_object($result)) {
					if($_data->brand != $row->bridx) {
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
				if($_data->brand>0) {
					@mysql_query("UPDATE tblproduct SET brand = null WHERE productcode = '".$prcode."'",get_db_conn());
				}
			}

			$groupdelete = mysql_query("DELETE FROM tblproductgroupcode WHERE productcode = '".$prcode."' ",get_db_conn());
			if($groupdelete) {
				if($group_check=="Y" && count($group_code)>0) {
					for($i=0; $i<count($group_code); $i++) {
						$sql = "INSERT tblproductgroupcode SET ";
						$sql.= "productcode = '".$prcode."', ";
						$sql.= "group_code = '".$group_code[$i]."' ";
						@mysql_query($sql,get_db_conn());
					}
				}
			}

			if($_data->display!=$display) {
				$sql = "UPDATE tblvenderstorecount ";
				if($display=="Y") {
					$sql.= "SET prdt_cnt=prdt_cnt+1 ";
				} else {
					$sql.= "SET prdt_cnt=prdt_cnt-1 ";
				}
				$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and productcode='".$prcode."' ";
				mysql_query($sql,get_db_conn());
			}

			if($vsellprice!=$sellprice) {
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
			}
			
			/*
			//gura :: 과금방식
			$sql = "SELECT * FROM product_rent ";
			$sql.= "WHERE pridx='".$pridx."'";
			$result=mysql_query($sql,get_db_conn());
			$_prdata=mysql_fetch_object($result);
			mysql_free_result($result);

			if($_prdata->pridx){
				$sql = "UPDATE product_rent SET ";
				$sql.= "pricetype = '".$pricetype."', ";
				$sql.= "useseason = '".$useseason."' ";
				$sql.= "where pridx = '".$pridx."' ";
				@mysql_query($sql,get_db_conn());
			}else{
				$sql = "INSERT product_rent SET ";
				$sql.= "pridx = '".$pridx."', ";
				$sql.= "pricetype = '".$pricetype."', ";
				$sql.= "useseason = '".$useseason."' ";
				@mysql_query($sql,get_db_conn());
			}
*/
			

			$sql = "SELECT * FROM vender_rent ";
			$sql.= "WHERE pridx='".$pridx."'";

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
				$sql2.= "where pridx='".$pridx."'";
				mysql_query($sql2,get_db_conn());

			}else{
				$sql2 = "insert vender_rent SET ";
				$sql2.= "vender				= '".$_VenderInfo->getVidx()."', ";
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
			}

if($useseason==0){
	$sql_ = "delete from vender_season_range where vender=".$_VenderInfo->getVidx()." and pridx='".$pridx."'";
	mysql_query($sql_,get_db_conn());

	$sql_2 = "delete from vender_holiday_list where vender=".$_VenderInfo->getVidx()." and pridx='".$pridx."'";
	mysql_query($sql_2,get_db_conn());
}

			
			//장기대여
			$dsql = "delete from vender_longrent where vender=".$_VenderInfo->getVidx(). " and pridx='".$pridx."'";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
				for($i=0;$i<count($_POST['longrent_sday']);$i++){
					if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
						$sql2 = "insert into vender_longrent set vender='".$_VenderInfo->getVidx()."',pridx='".$pridx."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
						mysql_query($sql2,get_db_conn());
					}
				}
			}

			//환불
			$dsql = "delete from vender_refund where vender=".$_VenderInfo->getVidx()." and pridx='".$pridx."'";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){
				for($i=0;$i<count($_POST['refundday']);$i++){
					if($_POST['refundpercent'][$i]>=0){
						$sql_refund = "insert into vender_refund set vender='".$_VenderInfo->getVidx()."',pridx='".$pridx."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
						mysql_query($sql_refund,get_db_conn());
					}
				}
			}
			
			//장기할인
			$dsql = "delete from vender_longdiscount where vender=".$_VenderInfo->getVidx()." and pridx='".$pridx."'";	
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
				for($i=0;$i<count($_POST['discrangeday']);$i++){
					if($_POST['discrangeday'][$i]>=0 && $_POST['discrangepercent'][$i]>=0){
						$sql_disc = "insert into vender_longdiscount  set vender='".$_VenderInfo->getVidx()."',pridx='".$pridx."',day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
						mysql_query($sql_disc,get_db_conn());
					}
				}
			}
			//gura


			$onload="<html></head><body onload=\"alert('상품정보 수정이 완료되었습니다.');parent.iForm.submit()\"></body></html>";

			$log_content = "## 상품수정 ## - 코드 $prcode - 상품 : $productname 가격 : $sellprice 수량 : $quantity 기타 : $etctype 적립금 : $reserve 날짜고정 : ".(($insertdate=="Y")?"Y":"N")." $display";
			$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content);
		} else {
			$onload="<html></head><body onload=\"alert('상품정보 수정중 오류가 발생하였습니다.')\"></body></html>";
		}
	} else {
		$onload="<html></head><body onload=\"alert('상품이미지의 총 용량이 ".ceil($file_size/1024)
		."Kbyte로 300K가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 300K입니다.\\n\\n"
		."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.')\"></body></html>";
	}
	echo $onload; exit;

//수수료 변경 요청 jdy
}else if($mode=="com") {

	/* 개별 수수료 저장 jdy */
	$up_rq_com = $_REQUEST['up_rq_com'];
	$up_rq_cost = $_REQUEST['up_rq_cost'];
	$up_rq_name = $_REQUEST['up_rq_name'];
	insertCommission($_VenderInfo->getVidx(), $prcode, $up_rq_com, $up_rq_cost, $up_rq_name, "0", "");
	/* 개별 수수료 저장 jdy */

	$onload="<html></head><body onload=\"alert('수수료 변경 요청이 완료되었습니다.');parent.iForm.submit()\"></body></html>";
	echo $onload; exit;

}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="/js/rental.js"></script>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script> var $j = jQuery.noConflict();</script>
<script language="JavaScript">
$j(document).ready(function() {
	$('input[name=istrust]').click(function() {
		if($j('input[name=istrust]:checked').val()=="0"){
			$("#trust_sel").removeAttr("disabled");
		}else{
			$('#trust_sel').attr("disabled", "disabled");
		}
	})
});


/*검색 키워드*/
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
			htmlView += "<input type=\"checkbox\" name=\""+idx+"_kw[]\" class=\"ck_"+idx+"\" value=\""+$(valInput).val()+"\" checked>";
			htmlView += $(valInput).val();
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
	}else{
		$(".ck_"+el).prop("checked",false);
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
/*검색 키워드*/

function changePrice(){
	$('input[name^=custPrice]').val(document.form1.base_price.value);

	cel = $('input[name^=custPrice]');
	del = $('input[name^=priceDiscP]');
	sel = $('input[name^=nomalPrice]');

	autoSolv(cel,del,sel);
	changePrice2();
}

function changePrice2(){
	bprice = document.form1.base_price.value;
	btime = document.form1.base_time.value;

	$('input[name^=timeover_price]').val(parseInt(bprice/btime));
}

function DeletePrdtImg(temp){
	if(confirm('해당 이미지를 삭제하시겠습니까?')){
		document.cForm.mode.value="delprdtimg";
		document.cForm.delprdtimg.value=temp-1;
		document.cForm.target="processFrame";
		document.cForm.submit();
	}
}

function formSubmit(mode) {

	oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

	if (document.form1.productname.value.length==0) {
		alert("상품명을 입력하세요.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>100) {
		alert('총 입력가능한 길이가 한글 50자까지입니다. 다시한번 확인하시기 바랍니다.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		alert("소비자가격을 입력하세요.");
		document.form1.consumerprice.focus();
		return;
	}
	if (isNaN(document.form1.consumerprice.value)) {
		alert("소비자가격을 숫자로만 입력하세요.(콤마제외)");
		document.form1.consumerprice.focus();
		return;
	}
	if (document.form1.sellprice.value.length==0) {
		alert("판매가격을 입력하세요.");
		document.form1.sellprice.focus();
		return;
	}
	if (isNaN(document.form1.sellprice.value)) {
		alert("판매가격을 숫자로만 입력하세요.(콤마제외)");
		document.form1.consumerprice.focus();
		return;
	}
	
	<?if($_data->rental=="2"){?>
	if(document.form1.pricetype.value=="day"){
		if(document.form1.halfday[0].checked==false && document.form1.halfday[1].checked==false){
			alert("당일 12시간 대여허용여부를 선택하세요.");
			document.form1.halfday[0].focus();
			return;
		}
		if(document.form1.oneday_ex[0].checked==false && document.form1.oneday_ex[1].checked==false && document.form1.oneday_ex[2].checked==false){
			alert("1일 초과시 과금기준을 선택하세요.");
			document.form1.oneday_ex[0].focus();
			return;
		}
/*
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
*/
	}

/*
	if(document.form1.pricetype.value=="time"){
		if( document.form1.base_price.value==""){
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
*/
	if(document.form1.pricetype.value=="checkout"){
		if( document.form1.checkin_time.value==""){
			alert("체크인 시간을 입력하세요.");
			document.form1.checkin_time.focus();
			return;
		}
		if( document.form1.checkout_time.value==""){
			alert("체크아웃 시간을 입력하세요.");
			document.form1.checkout_time.focus();
			return;
		}
	}
	<?}?>
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
	<? if($_data->rental != '2'){ ?>
	if (document.form1.checkquantity[2].checked==true) {
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
	<? }?>
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
	}

	filesize = Number(document.form1.size_checker.fileSize) + Number(document.form1.size_checker2.fileSize) + Number(document.form1.size_checker3.fileSize) ;
	if(filesize><?=$maxfilesize?>) {
		alert('올리시려고 하는 파일용량이 300K이상입니다.\n파일용량을 체크하신후에 다시 이미지를 올려주세요');
		return;
	}
	tempcontent = document.form1.content.value;
<?if ($predit_type=="Y" && false){ ?>
	if(mode=="update" && tempcontent.length>0 && tempcontent.indexOf("<")==-1 && tempcontent.indexOf(">")==-1 && !confirm("웹편집기 기능추가로 텍스트로만 입력하신 상세설명은\n줄바꾸기가 해제되어 쇼핑몰에서 다르게 보여질 수 있습니다.\n\n재입력하시거나 현재 쇼핑몰에서 해당 상품의 상세설명을\n그대로 마우스로 드래그하여 붙여넣기를 해서 재입력하셔야 합니다.\n\n위와 같이 수정하지 않고 저장하시려면 [확인]을 누르세요.")){
		return;
	}
<?}?>
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}

	if(confirm("상품 정보를 수정하시겠습니까?")) {
		document.form1.mode.value=mode;
		document.form1.target="processFrame";
		document.form1.submit();
	}
}
</script>

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckChoiceIcon(no){
	num = document.form1.iconnum.value;
	iconnum=0;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) iconnum++;
	}
	if(iconnum>3){
		alert('아이콘 꾸미기는 한상품에 3개까지 등록 가능합니다.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked==true) alert('상품 중간/작은 이미지가 큰이미지에서 자동 생성됩니다.\n\n기존의 중간/작은 이미지는 삭제됩니다.');
}

var shop="layer0";
var ArrLayer = new Array ("layer0","layer1","layer2");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<3;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<3;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<3;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
}

function SelectColor(){
	setcolor = document.form1.setcolor.value;
	var newcolor = showModalDialog("select_color.php?color="+setcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		document.form1.setcolor.value=newcolor;
		document.all.ColorPreview.style.backgroundColor = '#' + newcolor;
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

function BrandSelect() {
	window.open("product_brandselect.php","brandselect","height=400,width=420,scrollbars=no,resizable=no");
}

function FiledSelect(pagetype) {
	window.open("product_select.php?type="+pagetype,pagetype,"height=400,width=420,scrollbars=no,resizable=no");
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


function commissionRequest() {

	var form = document.form1;

	<? /* 수수료 관련 추가 jdy */?>
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

	<? /* 수수료 관련 추가 jdy */?>

/*
	if(form.rq_name.value.length==0) {
		alert("요청자를 입력해주세요.");
		form.rq_name.focus();
		return;
	}
*/

	if(confirm("수수료 변경을 요청하시겠습니까??")) {
		form.mode.value="com";
		form.target="processFrame";
		form.submit();
	}
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
		if(v=='disc' && orgv > 0 && discv > 0){
			sell.value = parseInt(orgv-orgv*(discv/100));
		}
	}

}



//-->
</SCRIPT>

<?
$productname = $_data->productname;
if(strlen($_data->option_quantity)>0) $searchtype=1;

// 특수옵션값을 체크한다.
$dicker = $dicker_text="";
if (strlen($_data->etctype)>0) {
	$etctemp = explode("",$_data->etctype);
	$miniq = 1;          // 최소주문수량 기본값 넣는다.
	$maxq = "";
	for ($i=0;$i<count($etctemp);$i++) {
		if ($etctemp[$i]=="BANKONLY")                    $bankonly="Y";        // 현금전용
		else if (substr($etctemp[$i],0,11)=="DELIINFONO=")     $deliinfono=substr($etctemp[$i],11);  // 배송/교환/환불정보 노출안함 정보
		else if ($etctemp[$i]=="SETQUOTA")               $setquota="Y";        // 무이자상품
		else if (substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);  // 최소주문수량
		else if (substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);  // 최대주문수량
		else if (substr($etctemp[$i],0,5)=="ICON=")      $iconvalue=substr($etctemp[$i],5);  // 최대주문수량
		else if (substr($etctemp[$i],0,9)=="FREEDELI=")  $freedeli=substr($etctemp[$i],9);  // 무료배송상품
		else if (substr($etctemp[$i],0,7)=="DICKER=") {  $dicker='Y'; $dicker_text=str_replace("DICKER=","",$etctemp[$i]); }  // 가격대체문구
	}
}
if(strlen($iconvalue)>0) {
	for($i=0;$i<strlen($iconvalue);$i=$i+2) {
		$iconvalue2[substr($iconvalue,$i,2)]="Y";
	}
}

if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_data->option1)){
	$optcode = substr($_data->option1,5,4);
	$_data->option1="";
	$_data->option_price="";
}

if($_data->brand>0) {
	$sql = "SELECT brandname FROM tblproductbrand WHERE bridx = '".$_data->brand."' ";
	$result = mysql_query($sql,get_db_conn());
	$_data2 = mysql_fetch_object($result);
	$_data->brandname = $_data2->brandname;
}

?>
<!-- 에디터용 파일 호출 -->
	<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
	<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
	<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
	<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
	<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
	<script type="text/javascript" src="/gmeditor/editor.js"></script>
	<script type="text/javascript" src="PrdtRegist.js.php"></script> 
	<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
	<style type="text/css">
  @import url("/gmeditor/common.css");
</style>
<!-- # 에디터용 파일 호출 -->

<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<colgroup>
<col width=190>
<col width=20>
<col>
<col width=20>
</colgroup>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15">
				<form name=form1 method=post enctype="multipart/form-data">
				<input type=hidden name=mode>
				<input type=hidden name=prcode value="<?=$prcode?>">
				<input type=hidden name=htmlmode value='wysiwyg'>
				<input type=hidden name=delprdtimg>
				<input type=hidden name=option1>
				<input type=hidden name=option2>
				<input type=hidden name=option_price>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">

				

				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>카테고리 선택</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>

					<table width=100% border=0 cellspacing=0 cellpadding=0>
						<tr height="22" align="center">
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">대분류</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">중분류</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">소분류</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">세분류</div></th>
						</tr>
					</tr>
					<tr>
						<td height=6 colspan=7></td>
					</tr>

					<tr>
						<td valign=top>
						<select name="code1" style="width:100%" onchange="javascript:ACodeSendIt(document.form1, this.options[this.selectedIndex]);" disabled>
<?
						$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
						$sql.= "WHERE codeB='000' AND codeC='000' ";
						$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$ctype=substr($row->type,-1);
							if($ctype!="X") $ctype="";
							echo "<option value=\"".$row->codeA."\" ctype='".$ctype."'";
							if($row->codeA==substr($code,0,3)) echo " selected";
							echo ">".$row->code_name."";
							if($ctype=="X") echo " (단일분류)";
							echo "</option>\n";
						}
						mysql_free_result($result);
?>
						</select>
						<input type="hidden" name="codeA_name" value="">
						</td>
						<td></td>
						<td valign=top>
						<iframe name="BCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>" width="100%" height="22" scrolling=no frameborder=no></iframe>
						<input type="hidden" name="codeB_name" value="">
						</td>
						<td></td>
						<td valign=top>
						<iframe name="CCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,6)?>&select_code=<?=$code?>" width="100%" height="22" scrolling=no frameborder=no></iframe>
						<input type="hidden" name="codeC_name" value="">
						</td>
						<td></td>
						<td valign=top>
						<iframe name="DCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,9)?>&select_code=<?=$code?>" width="100%" height="22" scrolling=no frameborder=no></iframe>
						<input type="hidden" name="codeD_name" value="">
						</td>
					</tr>
					</table>

					</td>
				</tr>

				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>상품정보</B><a href="javascript:document.location.reload()">[새로고침]</a></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<!-- 대여 상품 ( 상품 구분 ) -->
					<? //include("product_register.add.rent.php"); 											
					$categoryRentInfo = categoryRentInfo($code);					
					$rentProduct = rentProduct($_data->pridx);	
					
					if(_array($categoryRentInfo)){	
						$commi = rentCommitionByCategory($code,$_venderdata->vender);											
					}
					?>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<colgroup>
					<col width=130>
					<col width=300>
					<col width=95>
					<col>
					</colgroup>
					<? if(substr($_venderdata->grant_product,3,1)=="N") {?>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품진열</td>
						<td colspan="3" style="padding:7px 7px">
						<input type=radio id="idx_display1" name=display value="Y" <? if ($_data->display=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display1>보이기 [ON]</label>
						<img width=50 height=0>
						<input type=radio id="idx_display2" name=display value="N" <? if ($_data->display=="N") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display2>안보이기 [OFF]</label>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<? } else {?>

					<input type=hidden name=display value="<?=$_data->display?>">

					<? }?>					
					<!-- 대여 상품 ( 상품 구분 ) -->
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품 구분</td>
						<td colspan=3  style="padding:7px 7px"><? echo ($_data->rental == '2')?'대여상품':'판매상품';?><input type="hidden" name="goodsType" value="<?=$_data->rental?>" /></td>						
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><font color=FF4800>*</font> 상품명</td>
						<td colspan="3" style="padding:7px 7px"><input name=productname value="<?=ereg_replace("\"","&quot",$_data->productname)?>" maxlength=250 style="width:100%" onKeyDown="chkFieldMaxLen(250)"></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품홍보문구</td>
						<td colspan="3" style="padding:7px 7px"><input name="prmsg" value="<?=ereg_replace("\"","&quot",$_data->prmsg)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" style="width:100%"></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>


					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">등록/수정일</td>
						<td colspan="3" style="padding:7px 7px">
<?
						echo " ".str_replace("-","/",substr($_data->modifydate,0,16))."</font>\n";
						echo "(상품코드 : <font color=#003399>".$_data->productcode."</font>)";
						echo "&nbsp;&nbsp;&nbsp;<a href=\"http://".$_venderdata->shopurl."?productcode=".$_data->productcode."\" target=_blank><img src=\"images/icon_goprdetail.gif\" align=absmiddle border=0></font></a>";
?>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<? if($_data->rental == '2'){ ?>
					<!-- 대여 상품 ( 상품 구분 ) -->
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품구분 및<br /> 관리 형태</td>
						<td colspan="3" style="padding:7px 7px">
							<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />셀프관리 (수수료  <?=number_format($commi['self'])?>%)
							<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  onclick="javascript:toggleTrust()" />위탁관리 (위탁 수수료 <input type="text" name="maincommi" style="width:60px;text-align:right" value="<?=$rentProduct['maincommi']>0? number_format($rentProduct['maincommi']) : number_format($commi['main'])?>">%)
							<?
							if($rentProduct['istrust']=='0') $trust_disabled = "";
							else $trust_disabled = "disabled";

							if($_data->vender<>$_VenderInfo->getVidx()){//받은업체인 경우
								$trustTitle = "vender";
								echo "<input type=hidden name=trust_vender value='".$_VenderInfo->getVidx()."'>";
							}else{
								$trustTitle = "trust_vender";
								echo "<input type=hidden name=vender value='".$_VenderInfo->getVidx()."'>";
							}


							//수수료변경이 있는 경우 확인
							$sql_ = "SELECT productcode FROM tbltrustcommission ";
							$sql_.= "WHERE (trust_vender='".$_VenderInfo->getVidx()."' OR vender='".$_VenderInfo->getVidx()."') ";
							$sql_.= "AND modify_vender<>'".$_VenderInfo->getVidx()."' ";
							$sql_.= "AND status='1' AND productcode='".$prcode."'";
							$res_=mysql_query($sql_,get_db_conn());
							$_commi_data=mysql_fetch_object($res_);
							mysql_free_result($result);

							if($_commi_data->productcode>0){
								$sql_ = "UPDATE tbltrustcommission set status='2' ";
								$sql_.= "WHERE (trust_vender='".$_VenderInfo->getVidx()."' OR vender='".$_VenderInfo->getVidx()."') ";
								$sql_.= "AND modify_vender<>'".$_VenderInfo->getVidx()."' ";
								$sql_.= "AND status='1' AND productcode='".$prcode."'";
								mysql_query($sql_,get_db_conn());								
							}
							?>
							<select name="<?=$trustTitle?>" style="width:130px" id="trust_sel" <?=$trust_disabled?>>
								<option value="">위탁업체 선택하기</option>
								<?
								$sql = "SELECT * FROM tbltrustagree WHERE (take_vender='".$_VenderInfo->getVidx()."' OR give_vender='".$_VenderInfo->getVidx()."') AND approve='Y'";
								$result=mysql_query($sql,get_db_conn());
								while($row=mysql_fetch_object($result)) {

									if($_VenderInfo->getVidx()==$row->take_vender){//해당업체가 받은업체인 경우
										$trust_vender = $row->give_vender;
										$selected = $_data->vender==$trust_vender? "selected":"";
										$trust_gubun = "(받은위탁) ";
									}else{
										$trust_vender = $row->take_vender;
										$selected = $rentProduct['trust_vender']==$trust_vender? "selected":"";
										$trust_gubun = "(보낸위탁) ";
									}

									$sql = "SELECT * FROM tblvenderinfo WHERE vender='".$trust_vender."' ";
									$tRes=mysql_query($sql,get_db_conn());
									$tData=mysql_fetch_object($tRes);
								?>
								<option value="<?=$trust_vender?>" <?=$selected;?>><?=$trust_gubun.$tData->com_name?></option>
								<?
								}
								?>
							</select>

							<?
							if($_data->vender<>$_VenderInfo->getVidx()){//받은업체인 경우
							?>
							<select name="trust_approve">
								<option value="Y" <?=$rentProduct[trust_approve]=="Y"? "selected":"";?>>위탁승인</option>
								<option value="R" <?=$rentProduct[trust_approve]=="R"? "selected":"";?>>위탁거절</option>
								<option value="N" <?=$rentProduct[trust_approve]=="N"? "selected":"";?>>위탁신청</option>
							</select>
							<?
							}else{
							?>
							<input type="hidden" name="trust_approve" value="<?=$rentProduct[trust_approve]?>">
							<?
							}
							?>
<!--
							<? if($rentProduct['istrust']=='0'){ ?>
								<input type="hidden" name="istrust" value="0" />위탁관리 (수수료  <?=number_format($commi['main'])?>%)
							<? }else if($rentProduct['istrust']=='-1'){ ?>
								<input type="hidden" name="istrust" value="-1" />위탁승인 대기(수수료  <?=number_format($commi['main'])?>%)	
							<? }else{ ?>
								<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />셀프관리 (수수료  <?=number_format($commi['self'])?>%)&nbsp;<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> onclick="javascript:toggleTrust()" />위탁관리 요청 (수수료  <?=number_format($commi['main'])?>%)										
							<? } ?>
-->
							<br><span class="notice_blue">* 총관리자가 설정한 카테고리별 수수료 설정과 감면수수료가 반영되어 적용됩니다.</font>		
							<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed; margin-top:5px;">
								<tr>
									<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>상품 타입</th>
									<td style="text-align:left; width:60px; padding-left:5px;">
										<? if($rentProduct['istrust'] ==  '1'){ ?>
										<input type=radio id="itemType1" name="itemType" value="product" <? if($rentProduct['itemType'] != 'location') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label><br>
										<input type=radio id="itemType2" name="itemType" value="location" <? if($rentProduct['itemType'] == 'location') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label> &nbsp;
										<? }else{ echo ($rentProduct['itemType'] != 'location')?'상품':'장소';
										} ?>
									</td>
									<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
									<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>가격구분</th>
									<td valign="top" style="text-align:left;padding:0px">
										<?/* switch($categoryRentInfo['pricetype']){
												case 'time': echo '시간단위 요금'; break;
												case 'day': echo '하루(24시간)단위 요금'; break;
												case 'checkout': echo '숙박제(오후2시~오전11시) 요금'; break;
												default: echo '오류'; break;
										}*/ ?>
										<script language="javascript" type="text/javascript">
										$j("#trust_sel").focus(function(){
											//this.initialSelect = this.selectedIndex;
										});
										$j("#trust_sel").change(function(){
											//this.selectedIndex = this.initialSelect;
										});
										/*
										$j(function(){
											//요금퍼센트값으로 자동계산(가격은 디비에 저장되지 않음)
											del = $j('#price1').parent().find('input[name^=halfday_percent]');
											cel = $j('#rentOptTable').parent().parent().find('input[name^=nomalPrice]');
											sel = $j('#price1').parent().find('input[name^=halfday_per_price]');
											autoPrice(cel,del,sel);

											del = $j('#price2').parent().find('input[name^=time_percent]');
											cel = $j('#rentOptTable').parent().parent().find('input[name^=nomalPrice]');
											sel = $j('#price2').parent().find('input[name^=time_per_price]');
											autoPrice(cel,del,sel);

											$j('#price1').on('keyup','.autoSolv',function(e){
												var ptr = $j(this).parent();
												var ptr2 = $j('#rentOptTable').parent().parent();
												del = $j(ptr).find('input[name^=halfday_percent]');
												cel = $j(ptr2).find('input[name^=nomalPrice]');
												sel = $j(ptr).find('input[name^=halfday_per_price]');

												autoPrice(cel,del,sel);
											});
											$j('#price2').on('keyup','.autoSolv',function(e){
												var ptr = $j(this).parent();
												var ptr2 = $j('#rentOptTable').parent().parent();
												del = $j(ptr).find('input[name^=time_percent]');
												cel = $j(ptr2).find('input[name^=nomalPrice]');
												sel = $j(ptr).find('input[name^=time_per_price]');

												autoPrice(cel,del,sel);
											});

											$j('#price1').on('keyup','.autoPerSolv',function(e){
												var ptr = $j(this).parent();
												var ptr2 = $j('#rentOptTable').parent().parent();
												del = $j(ptr).find('input[name^=halfday_percent]');
												cel = $j(ptr2).find('input[name^=nomalPrice]');
												sel = $j(ptr).find('input[name^=halfday_per_price]');

												autoPercent(cel,del,sel);
											});
											$j('#price2').on('keyup','.autoPerSolv',function(e){
												var ptr = $j(this).parent();
												var ptr2 = $j('#rentOptTable').parent().parent();
												del = $j(ptr).find('input[name^=time_percent]');
												cel = $j(ptr2).find('input[name^=nomalPrice]');
												sel = $j(ptr).find('input[name^=time_per_price]');

												autoPercent(cel,del,sel);
											});
										});
										*/
										
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
/*
										function halfdayCheck(val){			
											if(val=="Y"){
												html = '<div>당일 12시간 요금: <br>24시간 요금의 ';
												html += '<input type="text" name="halfday_percent" class="autoSolv" size="3" maxlength="2">%';
												html += '<input type="text" name="halfday_per_price" class="autoSolv" size="5">원</div>';
												$j('#price1').html(html);
											}else{
												html = '';
												$j('#price1').html(html);
											}
											
										}

										function onedayexCheck(val){			
											if(val=="time"){
												html = '<div>추가 1시간 요금: <br>';
												html+= '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2">%';
												html+= '<input type="text" name="time_per_price" class="autoPerSolv" size="5">원';
												html+='</div>';
												$j('#price2').html(html);
											}else if(val=="half"){
												html = '<div>추가 12시간 요금: <br>';
												html+= '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2">%';
												html+= '<input type="text" name="time_per_price" class="autoPerSolv" size="5">원';
												html+='</div>';
												$j('#price2').html(html);
											}else{
												html = '';
												$j('#price2').html(html);
											}
											
										}
										*/
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
										?>
										<div style="padding:5px;border-bottom:1px solid #eeeeee;text-align:left">
											<select name="pricetype" id="pricetype" onchange="javascript:chPriceType()" style="width:120px">
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
									<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>성수기사용</th>
									<td style="text-align:left;padding:0px 10px">
										<?// echo ($categoryRentInfo['useseason'] == '1')?'성수기 사용':'사용안함';?>
										<input type=radio name=useseason value="0" <?if($categoryRentInfo['useseason']!="1")echo"checked";?> onclick="toggleSeasonList()">사용안함 <br>
										<input type=radio name=useseason value="1" <?if($categoryRentInfo['useseason']=="1")echo"checked";?> onclick="toggleSeasonList()">(비)성수기 사용
										<div id="seasonDiv" style="margin-top:5px;border:1px solid #efefefe"> 
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
											<? if($categoryRentInfo['useseason']=="1"){ $display=""; }else{ $display="none"; } ?>
											<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" class="infoListTbl" style="margin-top:7px;display:<?=$display?>">
												</tr>
													<td class="norbl" style="padding:5px;">
														<input type="button" value="성수기/준성수기 관리" style="width:200px;" onclick="window.open('vender_seasonpop.php?vender=<?=$_venderdata->vender?>&pridx=<?=$_data->pridx?>', 'busySeasonPop', 'width=800,height=600' );">
													</td>
												</tr>
												<tr>
													<td style="padding:5px;" class="norbl nobbl">
														<input type="button" value="공휴일/주말 관리" style="width:200px;"  onclick="window.open('vender_holiday.php?vender=<?=$_venderdata->vender?>&pridx=<?=$_data->pridx?>', 'holidayPop', 'width=800,height=600' );">
													</td>
												</tr>
											</table>
										</div>
									</td>																
								</tr>
							</table>
						</td>
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 소재지</td>
						<td colspan="3" style="padding:7px 7px">
						<? 
							if($rentProduct['istrust'] ==  '1'){ 
								// 대여 출고지 정보 리스트
								$value = array("vender"=>$_VenderInfo->getVidx()); // 노출 만 표시
								$localList = rentLocalList( $value );
								if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;							
								?>
								<script language="javascript" type="text/javascript">
									var popwin =null;
									function openLocalWin(){				
										popwin = window.open('/vender/rental/location.php','LocalWin','width=600,height=600');
									}
								</script>
								<select name="location" style="float:left">
									<option value="0">--소재지 선택--</option>
									<?
									if(_array($localList)){
										foreach ( $localList as $k=>$v ) { 
											$sel = ($rentProduct['location'] == $v['location'])?'selected="selected"':'';
										?>
									<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
									<?  }
									}
									 ?>
								</select>
								<input type="button" value="출고지 관리" onclick="javascript:openLocalWin();" style="margin-left:5px;" />
						
								
							<? }else if($rentProduct['istrust'] == '0'){
									$locinfo = array();
									if(_isInt($rentProduct['location'])) $locinfo = rentLocalList(array('location'=>$rentProduct['location']));
									if(_array($locinfo)){
										$locinfo = array_pop($locinfo);
										echo '<span style="font-weight:bold">[ '.$locinfo['title'].' ]</span>&nbsp;&nbsp;'.(strlen($locinfo['zip']) > 3?'('.$locinfo['zip'].')&nbsp;':'');
										echo $locinfo['address'];
									}																			
								?>
									<input type="hidden" name="location" value="<?=$rentProduct['location']?>" />
							<? }else if($rentProduct['istrust'] == '-1'){ ?>
								위탁 승인 후 본사에서 상품 보관 창고를 연락드립니다.
							<? } ?>
						</TD>
					</TR>

					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 당일예약가능여부</td>
						<td colspan="3" style="padding:7px 7px">
							<input type="radio" name="today_reserve" id="itemReserve1" value="Y" <?=($_data->today_reserve == 'Y')?'checked':''?> />
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve1>가능</label>
							<input type="radio" name="today_reserve" id="itemReserve2" value="N" <?=($_data->today_reserve == 'N')?'checked':''?> />
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve2>불가능</label>
						</TD>
					</TR>

					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> <font color="FF4800">*</font><span class="font_orange" style="font-weight:bold">대여가격</span></td>
						<td colspan="3" style="padding:7px">
							<style type="text/css">
								#rentOptTable{border-top:1px solid #b9b9b9;font-size:12px;}
								#rentOptTable th{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8; background-image:none; text-align:center}
								#rentOptTable .firstTh{border-left:none;background:#f8f8f8;}
								#rentOptTable td{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
								#rentOptTable .firstTd{padding-left:10px;border-left:none;}
								
								#rentOptTable2{border-top:1px solid #b9b9b9;font-size:12px;}
								#rentOptTable2 th{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8; background-image:none; text-align:center}
								#rentOptTable2 .firstTh{border-left:none;background:#f8f8f8;}
								#rentOptTable2 td{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
								#rentOptTable2 .firstTd{padding-left:10px;border-left:none;}
							</style>

							<div id="rentPriceArea">
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
								
								/*
								function rentOptInsert(){
									bel = $j('#rentPriceArea').find('textarea[name=optformatcode]');
									$j('#rentOptTable>tbody').append($j(bel).val());
									toggleSeasonList();
									toggleOptType();
								}
								*/
								function rentOptInsert(chktoggle){
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

								<div id="priceHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
									24시간 : <span id="priceHelp24"></span><br />
									12시간 : <span id="priceHelp12"></span><br />
									<? if(($categoryRentInfo['pricetype'] == 'day' && $_ptdata->oneday_ex=="time") || $categoryRentInfo['pricetype'] == 'time'){ ?> 추가 1시간 : <span id="priceHelp1"></span><? } ?>
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
								<table width="100%" border="0" cellpadding="0" cellspacing="0" id="rentOptTable" style="display:<?=$optTb1_display?>">
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
										$i=0;
										foreach($roptions as $roidx=>$roption){
											if($i==0) $cprice = $roption['nomalPrice'];
/*
											$roption['productTimeover_price'] = ($roption['nomalPrice']*$roption['productTimeover_percent'])/100;
											$roption['productHalfday_price'] = ($roption['nomalPrice']*$roption['productHalfday_percent'])/100;
											$roption['productOverHalfTime_price'] = ($roption['nomalPrice']*$roption['productOverHalfTime_percent'])/100;
											$roption['productOverOneTime_price'] = ($roption['nomalPrice']*$roption['productOverOneTime_percent'])/100;
											*/
									?>
										<tr class="itemSepRow">
											<td class="firstTd optMulti itemSepTD" align="center" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="optionName[]" style="width:80px;" class="input" value="<?=$roption['optionName']?>" <?=$optTb1_disabled?>/></td>
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
											<td align="center">일반가(비수기*평일)</td>
											<td style="text-align:center">
												<input type="text" name="custPrice[]" data-calc="org" style="width:60px" value="<?=$roption['custPrice']?>" class="input autoSolv"  <?=$optTb1_disabled?>/>원 - <input type="text" name="priceDiscP[]" data-calc="disc" style="width:30px" class="input autoSolv" value="<?=$roption['priceDiscP']?>" <?=$optTb1_disabled?> />%
											</td>
											<td style="text-align:center">
												<input type="text" name="nomalPrice[]" data-calc="sell" style="width:70px;" class="input autoPerSolv" value="<?=$roption['nomalPrice']?>" <?=$optTb1_disabled?> />원 <input type="button" class="priceHelp" style="width:30px;" value="?" />
												<input type="hidden" name="roptidx[]" value="<?=$roidx?>" <?=$optTb1_disabled?> />
											</td>
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
											<td align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
												<input type="text" name="productCount[]" style="width:50px;text-align:center;" class="input" value="<?=$roption['productCount']?>" <?=$optTb1_disabled?> />개
											</td>
											<td align="center" class="optMulti itemSepTD"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
												<input type="button" value="삭제" onclick="javascript:delOptitem(this)" />
												<!-- <a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a> --->
											</td>
										</tr>								
										<?// if($categoryRentInfo['useseason'] == '1'){ ?>
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
											<td style="text-align:center"><input type="text" name="holidaySeason[]" <?=$optTb1_disabled?> style="width:30px" class="input" value="<?=$roption['holidaySeason']?>" />%</td>
										</tr>
										<?// } ?>
									<?	
											$i++;
										}
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
										<td class="firstTd optMulti itemSepTD" align="center"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="optionName[]" style="width:80px;" class="input" /></td>
										<td align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<select name="optionGrade[]">
												<?
												foreach (rentProduct::_status() as $k=>$v) {
													echo "<option value='".$k."'>".$v."</option>";
												}
												?>
											</select>
										</td>				
										<td align="center">일반가(비수기*평일)</td>
										<td>
											<input type="text" name="custPrice[]" data-calc="org" value="0" style="width:60px" class="input autoSolv" />원 - <input type="text" name="priceDiscP[]" data-calc="disc" value="0" style="width:30px" class="input autoSolv" />%
										</td>
										<td>
											<input type="text" name="nomalPrice[]" data-calc="sell" style="width:80px;" value="0" class="input autoSolv" />원
											<input type="hidden" name="roptidx[]" value="" />
										</td>
										<td align="center" class="optTime itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="text" name="productTimeover_percent[]" value="" style="width:30px;" class="input autoSolv">% 
											<input type="text" name="productTimeover_price[]" value="" style="width:70px;" class="input autoPerSolv">원
										</td>
										<td align="center" class="optDay itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="text" name="productHalfday_percent[]" value="" style="width:30px;" class="input autoSolv">% 
											<input type="text" name="productHalfday_price[]" value="" style="width:70px;" class="input autoPerSolv">원
										</td>
										<td align="center" class="optDay2 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="text" name="productOverHalfTime_percent[]" value="" style="width:30px;" class="input autoSolv">% 
											<input type="text" name="productOverHalfTime_price[]" value="" style="width:70px;" class="input autoPerSolv">원
										</td>
										<td align="center" class="optDay3 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="text" name="productOverOneTime_percent[]" value="" style="width:30px;" class="input autoSolv">% 
											<input type="text" name="productOverOneTime_price[]" value="" style="width:70px;" class="input autoPerSolv">원
										</td>
										<td align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="text" name="productCount[]" value="0" style="width:60px;text-align:center;" class="input" />개
										</td>
										<td align="center" class="optMulti itemSepTD"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
											<input type="button" value="삭제" onclick="javascript:delOptitem(this)" />
											<!-- <a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a> --->
										</td>
									</tr>								
									<?// if($categoryRentInfo['useseason'] == '1'){ ?>									
									<tr class="seasonList" style="display:<?=$display?>">
										<td align="center">준성수기*평일 할증</td>	
										<td>&nbsp;</td>
										<td align="center"><input type="text" name="semiBusySeason[]" value="0" style="width:30px" class="input" />%</td>
									</tr>
									<tr class="seasonList" style="display:<?=$display?>">
										<td align="center">준성수기*주말공휴일 할증</td>			
										<td>&nbsp;</td>
										<td align="center"><input type="text" name="semiBusyholidaySeason[]" value="0" style="width:30px" class="input" />%</td>
									</tr>
									<tr class="seasonList" style="display:<?=$display?>">
										<td align="center">성수기*평일 할증</td>			
										<td>&nbsp;</td>
										<td align="center"><input type="text" name="busySeason[]" value="0" style="width:30px" class="input" />%</td>
									</tr>
									<tr class="seasonList" style="display:<?=$display?>">
										<td align="center">성수기*주말공휴일 할증</td>			
										<td>&nbsp;</td>
										<td align="center"><input type="text" name="busyHolidaySeason[]" value="0" style="width:30px" class="input" />%</td>
									</tr>
									<?// } ?>
									<tr class="seasonList" style="display:<?=$display?>">
										<td align="center">비수기*주말공휴일 할증</td>			
										<td>&nbsp;</td>
										<td align="center"><input type="text" name="holidaySeason[]" value="0" style="width:30px" class="input" />%</td>
									</tr>
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
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>				
					
					<? 
					$longrentinfo = venderLongrentCharge($_VenderInfo->getVidx(),$_data->pridx);		
					if(count($longrentinfo)==0){
						$longrentinfo2 = venderLongrentCharge($_VenderInfo->getVidx(),0);
						
						if(count($longrentinfo2)==0){
							$longrentinfo = rentLongrentCharge(pick($code,$parentcode));
						}else{
							$longrentinfo = $longrentinfo2;
						}
					}
					?>
					<tr class="rentalItemArea7" style="display:<?=($categoryRentInfo['pricetype']=='period'||$categoryRentInfo['pricetype']=='long')?"":"none";?>">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">장기대여 설정</td>
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
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>		
										
					<tr class="rentalItemArea rentalItemArea5" style="display:<?=($categoryRentInfo['pricetype'] != 'period')?"":"none";?>">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 장기할인 설정</td>
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
							$ldiscinfo = venderLongDiscount($_VenderInfo->getVidx(),$_data->pridx);
							if(count($ldiscinfo)==0){
								$ldiscinfo2 = venderLongDiscount($_VenderInfo->getVidx(),0);

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
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					
					<tr class="rentalItemArea">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 환불 설정</td>
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
								$refundinfo = venderRefundCommission($_VenderInfo->getVidx(),$_data->pridx);
							
								if(count($refundinfo)==0){
									$refundinfo2 = venderRefundCommission($_VenderInfo->getVidx(),0);
									
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
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr class="rentalItemArea10">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">예약 확정 방식</td>
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
					<tr class="rentalItemArea10" ><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr class="rentalItemArea8" style="display:<?=($categoryRentInfo['pricetype'] == 'long')?"":"none";?>">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">중도해지시 <br>해약 비용</td>
						<td colspan="3">
							<textarea name="cancel_cont" style="width:80%;height:120px"><?=$categoryRentInfo['cancel_cont']?></textarea>
						</td>
					</tr>
					<tr class="rentalItemArea8" style="display:<?=($categoryRentInfo['pricetype'] == 'long')?"":"none";?>"><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr class="rentalItemArea9" style="display:<?=($categoryRentInfo['pricetype'] == 'long')?"":"none";?>">
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">제휴카드 할인</td>
						<td colspan="3">
							<textarea name="discount_card" style="width:80%;height:50px"><?=$categoryRentInfo['discount_card']?></textarea>
						</td>
					</tr>
					<tr class="rentalItemArea9" style="display:<?=($categoryRentInfo['pricetype'] == 'long')?"":"none";?>"><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
				<?	}	?>

					<? /* 추가 jdy */?>
					<!-- <tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 혜택 및 구매제한</td>
						<td colspan="3" style="padding:7px 7px">
						<? if ($coupon_use=="1") { ?>
						<input type=checkbox id="idx_etcapply_coupon" name=etcapply_coupon value="Y" <?=($_data->etcapply_coupon=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_coupon>할인쿠폰 적용불가</label>
						&nbsp;&nbsp;&nbsp;
						<? }else{ ?>
						<input type="hidden" name=etcapply_coupon value="<?= $_data->etcapply_coupon ?>" />
						<? } ?>

						<? if ($reserve_use=="1") { ?>
						<input type=checkbox id="idx_etcapply_reserve" name=etcapply_reserve value="Y" <?=($_data->etcapply_reserve=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_reserve>적립금적용불가</label>
						&nbsp;&nbsp;&nbsp;
						<? }else{ ?>
						<input type="hidden" name=etcapply_reserve value="<?= $_data->etcapply_reserve ?>" />
						<? } ?>
						<input type=checkbox id="idx_etcapply_gift" name=etcapply_gift value="Y" <?=($_data->etcapply_gift=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_gift>구매사은품적용불가</label>
						<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($_data->etcapply_return=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>교환및환불 불가</label>
						<input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>현금결제만 사용하기</label> <font style="color:#2A97A7;font-size:8pt">(여러 상품과 함께 구매시 결제는 현금결제로만 진행됩니다.)</FONT>
						</td>
					</tr> -->

					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>	
					

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">회원등급별적립</td>					
						<td colspan="3"  style="padding:7px 7px">
						<?
						$groupdiscount = getGroupReserves($_data->productcode,$_VenderInfo->getVidx());					
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
						</script>
						<input type="button" value="변경요청" onclick="javascript:changeDiscount()"  />
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
							<!--input type="button" value="승인" onclick="javascript:confirmChange();" style="margin-right:5px;" /><input type="button" value="거부" onclick="javascript:rejectChange();" style="margin-right:5px;" /-->
						<?	} ?>
						
					</td>
				</tr>
				<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
				<tr>
					<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">추천인등급별 적립</td>					
					<td colspan="3"  style="padding:7px 7px">
						<?
						$groupdiscount2 = getGroupReseller_Reserves($_data->productcode,$_VenderInfo->getVidx());					
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
						</script>
						<input type="button" value="변경요청" onclick="javascript:changeDiscount2()"  />
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
							<!--input type="button" value="승인" onclick="javascript:confirmChange2();" style="margin-right:5px;" /><input type="button" value="거부" onclick="javascript:rejectChange2();" style="margin-right:5px;" /-->
						<?	} ?>
						
					</td>
				</tr>




<!--
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">회원등급별할인</td>					
						<td colspan="3"  style="padding:7px 7px">
							<?
							$groupdiscount = getGroupDiscounts($_data->productcode);
							$rgroupdiscount = getReqGroupDiscounts($_data->productcode);
							
							foreach($groupdiscount as $gdiscount){ ?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;<?=($gdiscount['discount'] <1)?($gdiscount['discount']*100).'%':$gdiscount['discount']?></span>
	<?						}
							?>
							<script language="javascript" type="text/javascript">
							function reqChangeDiscount(){
								var pwin = window.open("/admin/product_ext/pop_groupdiscount.php?code=<?=$_data->productcode?>","ReqWin","height=450,width=380,scrollbars=yes");
							}
							</script>
							<input type="button" value="변경요청" onclick="javascript:reqChangeDiscount();" /><br>
							<? if(_array($rgroupdiscount)){ 
								foreach($rgroupdiscount as $gdiscount){ ?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;<?=($gdiscount['discount'] <1)?($gdiscount['discount']*100).'%':$gdiscount['discount']?></span>
	<?							}	?>
								[변경요청중]
						<?		} ?>
						</td>
					</tr>
-->

					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<? /* 추가 jdy */?>

					<tr <? if($_data->rental =='2'){ ?> style="display:none" <? } ?>>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><font color=FF4800>*</font> <?=($gongtype=="Y"?"공구가":"판매가격")?></td>
						<td colspan="3" style="padding:7px 7px">

								판매가 : <input name=sellprice value="<?=(int)(strlen($_data->sellprice)>0?$_data->sellprice:"0")?>" size=16 maxlength=10 class="input" <?=($_data->assembleuse=="Y"?"disabled style='background:#C0C0C0'":"")?> style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('sell');" onfocus="sellpriceAutoCalc('sell');">원
								=
								정상가 : <input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('org');" onfocus="sellpriceAutoCalc('org');" >원
								-
								할인율 : <input name=discountRate value="<?=(int)(strlen($_data->discountRate)>0?$_data->discountRate:"0")?>" size=3 maxlength=3 class="input" style="text-align:center; font-weight:bold; width:40px;" onkeyup="sellpriceAutoCalc('disc');">%
								(<input type="checkbox" id="autoCalc"><label style='cursor:hand;' for="autoCalc">자동계산</label>)

								<br><span class="font_orange">* 정상가 <strike>5,000</strike>로 표기됨, 0 입력시 표기안됨.&nbsp;</span>

						</td>
					</tr>
					<tr <? if($_data->rental =='2'){ ?> style="display:none" <? } ?>><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>				
					<? /****** 수수료 관련 수정 jdy ************/?>
					<?

					if ($account_rule=="1" || $commission_type=="1") {
					//공급가로 운영되거나.. 수수료지만 개별 수수료일??만 나타남.

						$adjust_title = "개별 수수료";
						if($account_rule) $adjust_title = "개별 공급가";

					?>
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><font color=FF4800>*</font><?= $adjust_title ?></TD>
						<td colspan="3" style="padding:7px 7px">
						<? if ($account_rule=="1") {

								$cf_num = $_data->cf_cost;
								$rq_num = $_data->rq_cost;
								if ($cf_num=="") $cf_num="0";

								if ($_data->first_approval=="1") {

									$adjust = $_data->sellprice - $cf_num;

									$com_status = "<b>현재 공급가 <span class=\"font_blue\">".$cf_num."</span>원 (수수료 ".$adjust."원) </b>";
								}else{
									$com_status = "<b>승인대기</b>";
								}

								if ($_data->status == '') {
									$com_status = "";
								}else if ($_data->status == '1') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>공급가 요청중 <span class=\"font_blue\">".$rq_num."</span>원</b>";
								}else if ($_data->status == '3') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>공급가 요청거부 <span class=\"font_blue\">".$rq_num."</span>원</b>";
								}

								$com_title = "공급가";
								$com_input = "<input type=text name=up_rq_cost value\"\" size=10 maxlength=10 onkeyup=\"strnumkeyup(this)\" class=input>원";
							?>
							<?= $com_status ?>
							<br/>
							<font style="color:#2A97A7;font-size:8pt">* 수수료 = 판매가 - 승인상품공급가</font>
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
									$com_status = "";
								}else if ($_data->status == '1') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>수수료 요청중 <span class=\"font_blue\">".$rq_num."</span>%</b>";
								}else if ($_data->status == '3') {
									$com_status = "&nbsp;&nbsp;&nbsp;&nbsp;<b>수수료 요청거부 <span class=\"font_blue\">".$rq_num."</span>%</b>";
								}
								$com_title = "수수료";
								$com_input = "<input type=text name=up_rq_com value=\"\" size=3 maxlength=3 onkeyup=\"strnumkeyup(this)\" class=input>%";
							?>
							<?= $com_status ?>
						<? } ?>
						&nbsp;&nbsp;&nbsp;&nbsp;<button style="color:#ffffff;background-color:#000000;border:0;width:80px;height:25px;cursor:pointer" onclick="commissionDivView();">변경요청</button>
						<? if (!$_data->status) { ?>
						&nbsp;&nbsp;<span style="color:red;font-weight:bold">* <?= $adjust_title ?>가 지정되지 않았습니다. <?= $adjust_title ?>를 요청해주세요.</span>
						<? } ?>
						<br/>
						<div id="commission_div" style="position:absolute;width:450px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;display:none;margin-top:10px;">
							<div style="width:100%;text-align:right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionDivView('N');" >X</span></div>
							<div style="width:100%;margin-top:5px;">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
									<col width=100 />
									<col width= />
									<tr><td height=2 colspan="2" bgcolor=#808080></td></tr>
									<tr>
										<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><B><?= $com_title ?></td>
										<td style=padding:7,10>
											<?= $com_input ?>
										</td>
									</tr>
									<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
									<tr id="commission_all" >
										<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><B>요청자 이름</td>
										<td style=padding:7,10>
											<input type=text name=rq_name value="" size=10 class=input>
										</td>
									</tr>
									<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
									<tr><td></td>
										<td style="padding-top:10px;text-align:right;"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionRequest()">요청</span></td>
									</tr>
								</table>
							</div>
						</div>

						</TD>
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<?
					}
					?>					
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 제조회사</td>
						<td style="padding:7px 7px"><input name=production value="<?=$_data->production?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 원산지</td>
						<td style="padding:7px 7px"><input name=madein value="<?=$_data->madein?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(30)">&nbsp;<a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 브랜드</td>
						<td style="padding:7px 7px"><input name=brandname value="<?=$_data->brandname?>" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" align="absmiddle"></a><br>
						<font style="color:#2A97A7;font-size:8pt">※ 브랜드를 직접 입력시에도 등록됩니다.</font></td></td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 모델명</td>
						<td style="padding:7px 7px"><input name=model value="<?=$_data->model?>" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 구입원가</td>
						<td style="padding:7px 7px" colspan="3"><input name=buyprice value="<?=$_data->buyprice?>" size=18 maxlength=10></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 진열코드</td>
						<td style="padding:7px 7px" colspan="3"><input name=selfcode value="<?=$_data->selfcode?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(20)"><br><font style="color:#2A97A7;font-size:8pt">* 쇼핑몰에서 자동으로 발급되는 상품코드와는 별개로 운영상 필요한 자체상품코드를 입력해 주세요.</font></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 배송수단선택</td>
						<td style="padding:7px 7px" colspan="3">
						<?php
							$deli_type_checked = array(5);
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
						<input type="checkbox" name="deli_type[]" id="deli_place" value="장소예약" <?=$deli_type_checked[4]?> /><label for="deli_place">장소예약</label>
					</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 출시일</td>
						<td style="padding:7px 7px" colspan="3"><input name=opendate value="<?=$_data->opendate?>" size=18 maxlength=8>&nbsp;&nbsp;예) <?=DATE("Ymd")?>(출시년월일)<br>
						<font style="color:#2A97A7;font-size:8pt">* 가격비교 페이지 등 제휴업체 관련 노출시 사용됩니다.<br>* 잘못된 출시일 지정으로 인한 문제는 상점에서 책임지셔야 됩니다.</font></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
				<? if($_data->rental == '1'){ ?>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 수량</td>
						<td colspan="3" style="padding:7px 7px">
<?
						$quantity=$_data->quantity;
						if($_data->quantity==NULL) $checkquantity="F";
						else if($_data->quantity<=0) $checkquantity="E";
						else $checkquantity="C";
						if($quantity<0) $quantity="";

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
						echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\">개";

						if($checkquantity=="C"){
							echo "<script>document.form1.quantity.disabled=false;document.form1.quantity.style.background='white';</script>\n";
						}else{
							echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
						}
?>
						</td>
					</tr>
				<? } ?>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 최소주문한도</td>
						<td style="padding:7px 7px"><input type=text name=miniq value="<?=($miniq>0?$miniq:"1")?>" size=5 maxlength=5> 개 이상</td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 최대주문한도</td>
						<td style="padding:7px 7px">
						<input type=radio id="idx_checkmaxq1" name=checkmaxq value="A" <? if (strlen($maxq)==0 || $maxq=="?") echo "checked ";?> onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq1>무제한</label>&nbsp;<input type=radio id="idx_checkmaxq2" name=checkmaxq value="B" <? if ($maxq!="?" && $maxq>0) echo "checked"; ?> onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq2>수량</label>:<input name=maxq size=5 maxlength=5 value="<?=$maxq?>">개 이하
						<script>
						if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
						else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
						</script>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 개별배송비</td>
						<td colspan="3" style="padding:7px 7px">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td><input type=radio id="idx_deliprtype0" name=deli value="H" <?if($_data->deli_price<=0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype0>기본 배송비 <b>유지</b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_deliprtype2" name=deli value="F" <?if($_data->deli_price<=0 && $_data->deli=="F") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype2>개별 배송비 <b><font color="#0000FF">무료</font></b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_deliprtype1" name=deli value="G" <?if($_data->deli_price<=0 && $_data->deli=="G") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype1>개별 배송비 <b><font color="#38A422">착불</font></b></label>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td><input type=radio id="idx_deliprtype3" name=deli value="N" <?if($_data->deli_price>0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype3>개별 배송비 <b><font color="#FF0000">유료</font></b> <input type=text name=deli_price_value1 value="<?if($_data->deli_price>0 && $_data->deli=="N") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="Y") echo "disabled style='background:silver'";?> class="input">원</label>
								<br>
								<input type=radio id="idx_deliprtype4" name=deli value="Y" <?if($_data->deli_price>0 && $_data->deli=="Y") echo "checked";?> onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype4>개별 배송비 <b><font color="#FF0000">유료</font></b> <input type=text name=deli_price_value2 value="<?if($_data->deli_price>0 && $_data->deli=="Y") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="N") echo "disabled style='background:silver'";?> class="input">원 (구매수 대비 개별 배송비 증가 : <FONT COLOR="#FF0000"><B>상품구매수×개별 배송비</B></font>)</label>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품노출등급</td>
						<td colspan="3" style="padding:7px 7px">
<?
						if($_data->group_check=="Y") {
							$sql = "SELECT group_code FROM tblproductgroupcode WHERE productcode = '".$prcode."' ";
							$result = mysql_query($sql,get_db_conn());
							while($row = mysql_fetch_object($result)) {
								$group_code[$row->group_code] = "Y";
							}
							mysql_free_result($result);
						}
?>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td><input type=radio id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($_data->group_check!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">상품노출등급 미지정</label>&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">* 상품노출등급 미지정할 경우 모든 비회원, 회원에게 노출됩니다.</font><br><input type=radio id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($_data->group_check=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">상품노출등급 지정</label></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr id="group_checkidx" <?if($_data->group_check!="Y") echo "style=\"display:none;\"";?>>
							<td>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<td bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
<?
								$sqlgrp = "SELECT group_code,group_name FROM tblmembergroup ";
								$resultgrp = mysql_query($sqlgrp,get_db_conn());
								$grpcnt=0;
								while($rowgrp = mysql_fetch_object($resultgrp)){
									if($grpcnt!=0 && $grpcnt%4==0) {
										echo "</tr>\n<tr>\n";
									}
									echo "<td width=\"25%\" style=\"padding:3px;\"><input type=checkbox id=\"group_code_idx".$grpcnt."\" name=\"group_code[]\" value=\"".$rowgrp->group_code."\"".(strlen($group_code[$rowgrp->group_code])>0?"checked":"")."> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"group_code_idx".$grpcnt."\">".$rowgrp->group_name."</label></td>\n";
									$grpcnt++;
								}
								mysql_free_result($resultgrp);

								if($grpcnt==0) {
									echo "<td style=\"padding:3px;\">* 회원등급이 존재하지 않습니다.</td>\n";
								}
?>
								</tr>
								</table>
								</td>
							</tr>
<?
							if($grpcnt!=0) {
								echo "<tr><td align=\"right\"><input type=checkbox id=\"group_codeall_idx\" onclick=\"GroupCodeAll(this.checked,$grpcnt);\"> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"group_codeall_idx\">일괄선택/해제</label></td></tr>\n";
							}
?>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 상품정보고시</td>
						<td colspan="3" style="padding:7,7">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td class="td_con1">
										상품구분 선택 :
										<select name="gosiTemplet" class="select">
											<option value="">템플릿 리스트 로딩중</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="td_con1">
										<span class="font_orange">
										＊ 항목명 또는 내용 중 한 부분이라도 내용이 없을경우 해당 항목은 등록되지 않습니다.<br>
										＊ 상품 구분선택을 통한 정보고시 내용은 기본 설정된 각 부분별 내용으로 필요시 수정이 가능합니다.<br>
										＊ 정보고시 내용 변경시 기존 등록 내용은 초기화되며, 상품 정보 저장시 적용됩니다.
										</span>
									</td>
								</tr>
								<tr>
									<td class="td_con1">



										<style type="text/css">
		/*
										.tblStyle1{ border-left:1px solid #ccc; border-top:1px solid #ccc; width:100%}
										.tblStyle1 th{ background:#efefef; padding:3px; border-right:1px solid #ccc;  border-bottom:1px solid #ccc; width:160px}
										.tblStyle1 td{  padding:3px; border-right:1px solid #ccc; border-bottom:1px solid #ccc;}
		*/
										.dtitleTd{ padding:0px 0px 0px 10px; background-color:#f5f5f5; }
										.daccTd{ padding:0px 0px 8px 10px; }
										.dbtnTd{ padding:10px 0px 10px 0px; }
										.dtitleInput{ width:96%; border:1px solid #ccc; font-family:돋움; letter-spacing:-1px; }
										.ditemTextarea{ width:98%; line-height:18px;}
										</style>

										<script language="javascript" type="text/javascript">
											function addGosiItem(el,itm){
												var str = '<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>';
													str += '<tr>';
													str +=      '<td class="dtitleTd"><input type="hidden" name="didx[]" value="" /><input type="text" name="dtitle[]" value="'+((itm && itm.title)?itm.title:'')+'" class="dtitleInput" /></td>';
													str +=      '<td width="60%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"></textarea></td>';
												if(itm && itm.desc){
													str +=      '<td width="90" class="dbtnTd" rowspan="2"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td></tr>';
													str += '<tr><td colspan="2" class="daccTd"><span class="font_orange">* '+itm.desc+'</span></td></tr>';
												}else{
													str +=      '<td class="dbtnTd"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td></tr>';
												}

												if(el){
													//$(el).parent().parent().after(str);
													var cel = $j(el).parent().parent();
													var nel = $j(cel).next('tr');
													if($j(nel).find('td:eq(0)').attr('colspan') == '2'){
														$j(nel).after(str);
													}else{
														$j(cel).after(str);
													}
												}else{
													if($j('#detailTable').find('tr').length <1){
														$j('#detailTable').append('<tbody>'+str+'</tbody>');
													}else{
														$j('#detailTable').find('tr:last').after(str);
													}
												}
												if($j('#detailTable').css('display') == 'none') $j('#detailTable').css('display','');
												parent_resizeIframe('AddFrame');
											}

											function removeGosiItem(el){
												var cel = $j(el).parent().parent();
												var nel = $j(cel).next('tr');
												if($j(nel) && $j(nel).find('td:eq(0)').attr('colspan') == '2'){
													$j(nel).remove();
												}
												$j(cel).remove();

												//$j(el).parent().parent().remove();
												if($j('#detailTable').find('tr').length <1){
													$j('#detailTable').css('display','none');
												}
											}

											$j(function(){
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
										<table width="98%" border="0" cellpadding="0" cellspacing="0" id="detailTable" style="margin:0px 10px 0px 15px; display:<?=(count($detialItems)>0)?'':'none'?>; border-top:1px solid #dddddd">
											<? if(count($detialItems)>0){
														foreach($detialItems as $ditem){ ?>
											<tr>
												<td class="dtitleTd"><input type="hidden" name="didx[]" value="<?=$ditem['didx']?>" /><input type="text" name="dtitle[]" value="<?=$ditem['dtitle']?>" class="dtitleInput" /></td>
												<td width="60%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"><?=$ditem['dcontent']?></textarea></td>
												<td width="90" class="dbtnTd"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="항목삭제" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="항목추가" style="cursor:hand;" /></td>
											</tr>
											<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>
											<?           } // end foreach
											} // end if
										?>
										</table>
									</td>
								</tr>
							</table>
						</TD>
					</tr>

					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

<?
					unset($specname);
					unset($specvalue);
					unset($specarray);
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
?>
<? /*
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 사용자 정의 스펙</TD>
						<td colspan="3" style="padding:7px 7px">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<colgroup>
							<col width="180">
							<col width="">
							</colgroup>
						<tr>
							<td colspan="2"><input type=radio id="idx_userspec1" name=userspec onclick="userspec_change('N');" value="N" <?if($userspec!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec1>사용자 정의 스펙 사용안함</label>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=radio id="idx_userspec0" name=userspec onclick="userspec_change('Y');" value="Y" <?if($userspec=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec0>사용자 정의 스펙 사용함</label></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr id="userspecidx" <?=($userspec=="Y"?"":"style='display:none;'")?>>
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
								<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td>
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
									<colgroup>
									<col width="20">
										<col>
									</colgroup>
								<?for($i=0; $i<$userspec_cnt; $i++) {?>
								<tr>
									<td style="padding:5px;padding-bottom:0px;padding-left:7px;padding-right:2px;" align="center"><?=str_pad(($i+1), 2, "0", STR_PAD_LEFT);?>.</td>
									<td style="padding:5px;padding-bottom:0px;padding-left:0px;"><input name=specname[] value="<?=htmlspecialchars($specname[$i])?>" size=30 maxlength=30 style="width:100%;"></td></td>
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
								<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<?for($i=0; $i<$userspec_cnt; $i++) {?>
							<tr>
								<td style="padding:5px;padding-bottom:0px;"><input name=specvalue[] value="<?=htmlspecialchars($specvalue[$i])?>" size=50 maxlength=100 style="width:100%;"></td>
							</tr>
							<?}?>
							<tr>
								<td height="10"></td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</TD>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr> */ ?>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 검색어</td>
						<td colspan="3" style="padding:7px 7px">
						<input name=keyword value="<? if ($_data) echo $_data->keyword; ?>" size=80 maxlength=100 onKeyDown="chkFieldMaxLen(100)">
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 검색 키워드</td>
						<td colspan="3" style="padding:7px 7px">
							<div class="kw_view">
								<ul>
									<li>
										<span>사용</span>
										<span>분류</span>
										<span>검색 키워드</span>
									</li>
									<?
									//등록된 키워드가 있는 경우 
									$codeA = substr($code,0,3)."000000000";
									$codeB = substr($code,0,6)."000000";
									$codeC = substr($code,0,9)."000";


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
										//echo "<span><button type=\"button\" onclick=\"delKwGroup('".$kg_idx."')\" style=\"margin:2px;\">X</button></span>";
										echo "</span>";
										echo "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup;
										//echo "<button type=\"button\" onclick=\"delKwGroup('".$krow->kg_idx."')\" style=\"margin:2px;\">X</button> ";
										echo "</span>";
																					
										echo "<span id=\"".$krow->kg_idx."_kwlist\">";
										//echo "<input type=\"checkbox\" name=\"ckall_".$krow->kg_idx."\" id=\"ckall_".$krow->kg_idx."\" value=\"Y\" onclick=\"javascript:kwcheckAll('".$krow->kg_idx."')\"> 전체";
										
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


											//echo "<input type=\"checkbox\" name=\"".$krow->kg_idx."_kw[]\" class=\"ck_".$krow->kg_idx."\" value=\"".$krow->kwgroup.":".$krow2->keyword."\" ".$checked." oncheck=\"addkeyword('".$krow->kwgroup."','".$krow2->keyword."')\">";
											//echo $krow2->keyword;
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
									?>
								</ul>
							</div>
							<!--
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
							</div>-->
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
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품 특이사항</td>
						<td colspan="3" style="padding:7px 7px">
						<input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=43 maxlength=200 onKeyDown="chkFieldMaxLen(200)">&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">(예: 향수는 용량표시, TV는 17인치등)</font>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>사진정보</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<colgroup>
					<col width=130>
					<col>
						</colgroup>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 썸네일 & <br/> 상품이미지</td>
						<td style="padding:7px 7px">
						<input type="hidden" name="imgcheck" value="Y"  />
						<input type=file name="userfile" class=button style="width=300px" onchange="document.getElementById('size_checker').src=this.value;"> <font style="color:#2A97A7;font-size:8pt">(권장이미지 : 550X550)</font><font style="color:#FF0000;font-size:9pt;">(이미지를 등록하셔야 썸네일 이미지가 출력됩니다.)</font>
<?						if (strlen($_data->maximage)>0 && file_exists($imagepath.$_data->maximage)==true) {
							echo "<br><img src='".$imagepath.$_data->maximage."' height=100 width=200 border=1 alt='URL : /".RootPath.DataDir."shopimages/product/".$_data->maximage."'>";
							echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('1')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
						} else {
							echo "<br><img src=images/space01.gif>";
						} ?>
						<input type=hidden name="vimage" value="<?=$_data->maximage?>">					
						<input type=hidden name="vimage2" value="<?=$_data->minimage?>">					
						<input type=hidden name="vimage3" value="<?=$_data->tinyimage?>">					
						</td>
					</tr>
					<tr>
						<td bgcolor="F5F5F5" style="background-repeat:repeat-y;background-position:right;padding:9"> 추가 이미지&amp;컨텐츠</td>
						<td style="padding:7px 7px">
							<script language="javascript" type="text/javascript">
								function bookingSchedulePop (pridx,isadmin){											
									window.open("/admin/product_ext/multicontents.php?pridx="+pridx,"ContentMG","width=600,height=600,scrollbars=yes");
								}
							</script>

							<input type="button" value="추가 컨텐츠 및 이미지 관리하기" onclick="bookingSchedulePop('<?=_isInt($_data->pridx)?$_data->pridx:$chkstamp?>','1');">
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>상품 상세정보</B>

					</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr>
						<td>
							<!--<textarea wrap=off style="width:100%; height:300" name="content"  lang="ej-editor1"><?=htmlspecialchars($_data->content)?></textarea>-->



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



						</td>
					</tr>
					<tr>
						<td>
						<img id="size_checker" style="display:none;">
						<img id="size_checker2" style="display:none;">
						<img id="size_checker3" style="display:none;">
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</tr>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit('update')"><img src="images/btn_modify06.gif" border=0></A>
					</td>
				</tr>

				<input type=hidden name=iconnum value='<?=$totaliconnum?>'>
				<input type=hidden name=iconvalue>		

				</table>
				</form>

				<form name=cForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=mode>
				<input type=hidden name=prcode value=<?=$prcode?>>
				<input type=hidden name=delprdtimg>
				<input type=hidden name="vimage" value="<?=$_data->maximage?>">
				<input type=hidden name="vimage2" value="<?=$_data->minimage?>">
				<input type=hidden name="vimage3" value="<?=$_data->tinyimage?>">
				<input type=hidden name="attechwide" value="<?=$_data->wideimage?>">
				</form>

				<form name=iForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=prcode value=<?=$prcode?>>
				</form>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>

<?
if ($predit_type=="Y" && false) {
?>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script language="JavaScript">

$j(':checkbox[name="deli_type[]"]').click(function() {
	if ($j(':checkbox[name="deli_type[]"]:checked').length == 0) {
		alert('최소 하나의 배송 수단을 선택해야 합니다.\n[택배]를 자동선택합니다.');
		$j('#deli_parsel').attr('checked', true);
	}
});

function htmlsetmode(mode,i){
	if(mode==document.form1.htmlmode.value) {
		return;
	} else {
		i.checked=true;
		editor_setmode('content',mode);
	}
	document.form1.htmlmode.value=mode;
}
_editor_url = "htmlarea/";
editor_generate('content');
</script>
<?
}
?>

<?
if($searchtype==2 || $optionover=="YES") {
	echo "<script>document.form1.searchtype[2].checked=true;\nViewLayer('layer2');</script>";
} else if($searchtype==1) {
	echo "<script>document.form1.searchtype[1].checked=true;\nViewLayer('layer1');</script>";
}
?>

<? INCLUDE "copyright.php"; ?>
