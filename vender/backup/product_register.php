<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include ("access.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/admin_more.php");

if(substr($_venderdata->grant_product,0,1)!="Y") {
	echo "<html></head><body onload=\"alert('상품 등록 권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');history.go(-1)\"></body></html>";exit;
}

if($_venderdata->product_max!=0) {
	$sql = "SELECT prdt_allcnt FROM tblvenderstorecount WHERE vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$prdt_allcnt=$row->prdt_allcnt;

	if($_venderdata->product_max<=$prdt_allcnt) {
		echo "<html></head><body onload=\"alert('해당 미니샵에서 등록할 수 있는 상품갯수는 ".$_venderdata->product_max."개 입니다.\\n\\n다른상품을 삭제 후 등록하시거나 쇼핑몰에 문의하시기 바랍니다. ');history.go(-1)\"></body></html>";exit;
	}
}

$userspec_cnt=5;
$maxfilesize="308000";
$mode=$_POST["mode"];
$code=$_POST["code"];

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

// 테두리 설정에 대한 부분을 쿠키로 고정시킨다.
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	SetCookie("imgborder","Y",0,"/".RootPath.VenderDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && $mode=="insert") {
	SetCookie("imgborder","",time()-3600,"/".RootPath.VenderDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}
// 쿠키 끝


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

// 정산 기준, 적립금, 쿠폰 사용여부 조회 jdy

if($mode=="insert" && strlen($code)==12) {
	//분류 확인
	$sql = "SELECT type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' AND codeB='".substr($code,3,3)."' ";
	$sql.= "AND codeC='".substr($code,6,3)."' AND codeD='".substr($code,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if(substr($row->type,-1)!="X") {
			echo "<html></head><body onload=\"alert('상품을 등록할 분류 선택이 잘못되었습니다.')\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('상품을 등록할 분류 선택이 잘못되었습니다.')\"></body></html>";exit;
	}
	mysql_free_result($result);

	$prmsg=$_POST["prmsg"];

	$productname=$_POST["productname"];
	$option1=$_POST["option1"];
	$option1_name=$_POST["option1_name"];
	$option2=$_POST["option2"];
	$option2_name=$_POST["option2_name"];
	$consumerprice=$_POST["consumerprice"];
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
	$imgcheck=$_POST["imgcheck"];
	$deliinfono=$_POST["deliinfono"];	// 배송/교환/환불정보 노출안함 (Y)
	$miniq=$_POST["miniq"];			// 최소주문가능
	$maxq=$_POST["maxq"];			// 최대주문가능
	$content=$_POST["content"];

	$userspec=$_POST["userspec"];
	$specname=$_POST["specname"];
	$specvalue=$_POST["specvalue"];

	$group_check=$_POST["group_check"];
	$group_code=$_POST["group_code"];

	/* 추가 jdy */
	$insertdate=$_POST["insertdate"];

	$etcapply_coupon=$_POST["etcapply_coupon"];
	$etcapply_reserve=$_POST["etcapply_reserve"];
	$etcapply_gift=$_POST["etcapply_gift"];
	$etcapply_return=$_POST["etcapply_return"];
	if($etcapply_coupon!="Y") $etcapply_coupon="N";
	if($etcapply_reserve!="Y") $etcapply_reserve="N";
	if($etcapply_gift!="Y") $etcapply_gift="N";
	if($etcapply_return!="Y") $etcapply_return="N";

	$bankonly=$_POST["bankonly"];


	//예약 판매 상품 정보
	$reservation = ( $_POST["reservation"] == "Y" AND strlen($_POST["reservationDate"]) > 0 ) ? $_POST["reservationDate"] : '' ;


	/* 추가 jdy */

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

	if(strlen($display)==0) $display="Y";

	if((int)$opendate<1) $opendate="";

	$searchtype=$_POST["searchtype"];
	if(strlen($searchtype)==0) $searchtype=0;

	$userfile = $_FILES["userfile"];
	$userfile2 = $_FILES["userfile2"];
	$userfile3 = $_FILES["userfile3"];

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

	$imagepath=$Dir.DataDir."shopimages/product/";

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
			echo "<html></head><body onload=\"alert('상품코드를 생성하는데 실패했습니다. 잠시후 다시 시도하세요.')\"></body></html>";exit;
		}
		mysql_free_result($result);
	}else {
		$productcode = "000001";
	}

	$image_name = $code.$productcode;

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
		$content = ereg_replace("\\\\'","''",$content);
		$prmsg = ereg_replace("\\\\'","''",$prmsg);

		$message="";

		if($imgcheck=="Y") $filename = array (&$userfile[name],&$userfile[name],&$userfile[name]);
		else $filename = array (&$userfile[name],&$userfile2[name],&$userfile3[name]);
		$file = array (&$userfile[tmp_name],&$userfile2[tmp_name],&$userfile3[tmp_name]);
		$vimagear = array (&$vimage,&$vimage2,&$vimage3);
		$imgnum = array ("","2","3");


		for($i=0;$i<3;$i++){
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

		if(strlen($buyprice) < 1 ) $buyprice = 0 ;
		$result = mysql_query("SELECT COUNT(*) as cnt FROM tblproduct",get_db_conn());
		if ($row=mysql_fetch_object($result)) $cnt = $row->cnt;
		else $cnt=0;
		mysql_free_result($result);

		#와이드 이미지 추가
		$savewideimage = $Dir.DataDir."shopimages/wideimage/";
		if(!_empty($_FILES['wideimage']['name'])){
			$wmaxfilesize="2097152";
			$attechfilename=$widefilename="";
			$tempext=$widefileext=array();
			$widesaveloc = $_SERVER['DOCUMENT_ROOT']."/data/shopimages/wideimage/";
			$allowimagefile = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png','image/gif');
			$tempext = pathinfo($_FILES['wideimage']['name']);
			$widefileext = strtolower($tempext['extension']);
			$widefilename = $code.$productcode.".".$widefileext;
			if(is_file($savewideimage.$widefilename) && $mode=="modify"){
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

		/** 에디터 관련 파일 처리 추가 부분 */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$content = str_replace('/data/editor_temp/','/data/editor/',$content);
		}
		/** #에디터 관련 파일 처리 추가 부분 */

		$sql = "INSERT tblproduct SET ";
		$sql.= "productcode		= '".$code.$productcode."', ";
		$sql.= "assembleuse		= 'N', ";
		$sql.= "assembleproduct	= '', ";
		$sql.= "productname		= '".$productname."', ";
		$sql.= "prmsg		= '".$prmsg."', ";
		$sql.= "sellprice		= ".$sellprice.", ";
		$sql.= "consumerprice	= ".$consumerprice.", ";
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
		if(strlen($attechfilename)>0){
			$sql.= "wideimage		= '".$attechfilename."', ";
		}
		if($searchtype!=0) {
			$sql.= "option_price	= '".$option_price."', ";
			$sql.= "option_quantity	= '".$optcnt."', ";
			$sql.= "option1			= '".$option1."', ";
			$sql.= "option2			= '".$option2."', ";
		}
		$sql.= "etctype			= '".$etctype."', ";
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";

		$sql.= "reservation		= '".$reservation."', ";

			if(substr($_venderdata->grant_product,3,1)=="N") {

				//개별 수수료, 공급가일 경우는 상품을 전시하지 않는다. jdy
				if ($account_rule=="1" || $commission_type=="1") {
					$display="N";
					$sql.= "display		= 'N', ";
				}else{
					$sql.= "display		= '".$display."', ";
				}

			} else {
				$display="N";
				$sql.= "display		= 'N', ";
			}


		$sql.= "date			= '".$curdate."', ";
		$sql.= "vender			= '".$_VenderInfo->getVidx()."', ";
		$sql.= "regdate			= now(), ";
		$sql.= "modifydate		= now(), ";


		/* 추가 jdy */
		$sql.= "etcapply_coupon	= '".$etcapply_coupon."', ";
		$sql.= "etcapply_reserve= '".$etcapply_reserve."', ";
		$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
		$sql.= "etcapply_return	= '".$etcapply_return."', ";

		/* 추가 jdy */



		$sql.= "content			= '".$content."' ";
		if($insert = mysql_query($sql,get_db_conn())) {

			/* 개별 수수료 저장 jdy */
			$up_rq_com = $_REQUEST['up_rq_com'];
			$up_rq_cost = $_REQUEST['up_rq_cost'];
			$up_rq_name = $_REQUEST['up_rq_name'];
			insertCommission($_VenderInfo->getVidx(), $code.$productcode, $up_rq_com, $up_rq_cost, $up_rq_name, "0", '');
			/* 개별 수수료 저장 jdy */

			$sql = "insert into tblcategorycode set productcode='".$code.$productcode."',categorycode='".$code."'";
			@mysql_query($sql,get_db_conn());

			// 상품 정보 고시 관련 추가
			$pridx = mysql_insert_id(get_db_conn());
			$ditems = array();
			foreach($_REQUEST['didx'] as $k=>$v){
				$item = array();
				$item['didx'] = $v;
				$item['dtitle'] = $_REQUEST['dtitle'][$k];
				$item['dcontent'] = $_REQUEST['dcontent'][$k];
				array_push($ditems,$item);
			}
			_editProductDetails($pridx,$ditems);
			// #상품 정보 고시 관련 추가

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

			if($group_check=="Y" && count($group_code)>0) {
				for($i=0; $i<count($group_code); $i++) {
					$sql = "INSERT tblproductgroupcode SET ";
					$sql.= "productcode = '".$code.$productcode."', ";
					$sql.= "group_code = '".$group_code[$i]."' ";
					mysql_query($sql,get_db_conn());
				}
			}

			$sql = "UPDATE tblvenderstorecount SET prdt_allcnt=prdt_allcnt+1 ";
			if($display=="Y") {
				$sql.= ",prdt_cnt=prdt_cnt+1 ";
			}
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			mysql_query($sql,get_db_conn());
			$sql = "INSERT tblvendercodedesign SET ";
			$sql.= "vender		= '".$_VenderInfo->getVidx()."', ";
			$sql.= "code		= '".substr($code,0,3)."', ";
			$sql.= "tgbn		= '10', ";
			$sql.= "hot_used	= '1', ";
			$sql.= "hot_dispseq	= '118' ";
			@mysql_query($sql,get_db_conn());

			$onload="<html></head><body onload=\"alert('상품 등록이 완료되었습니다.');parent.location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";

			$log_content = "## 상품입력 ## - 코드 $code$productcode - 상품 : $productname 가격 : $sellprice 수량 : $quantity 기타 : $etctype 적립금: $reserve 날짜고정 : ".(($insertdate=="Y")?"Y":"N")." $display";
			$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content);
		} else {
			$onload="<html></head><body onload=\"alert('상품 등록중 오류가 발생하였습니다.')\"></body></html>";
		}
		$prcode=$code.$productcode;
	} else {
		$onload="<html></head><body onload=\"alert('상품이미지의 총 용량이 ".ceil($file_size/1024)
		."Kbyte로 300K가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 300K입니다.\\n\\n"
		."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.')\"></body></html>";
	}

	echo $onload; exit;
}
?>
<? include "header.php"; ?>
	<script type="text/javascript" src="lib.js.php"></script>
	<script type="text/javascript" src="calendar.js.php"></script>
	<script type="text/javascript" src="PrdtRegist.js.php"></script>
	<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script> var $j = jQuery.noConflict();</script>
	<script language="JavaScript">
function formSubmit(mode) {
	if(document.form1.code.value.length!=12) {
		codelen=document.form1.code.value.length;
		if(codelen==0) {
			alert("상품을 등록할 대분류를 선택하세요.");
			document.form1.code1.focus();
		} else if(codelen==3) {
			alert("상품을 등록할 중분류를 선택하세요.");
			BCodeCtgr.form1.code.focus();
		} else if(codelen==6) {
			alert("상품을 등록할 소분류를 선택하세요.");
			CCodeCtgr.form1.code.focus();
		} else if(codelen==9) {
			alert("상품을 등록할 세부분류를 선택하세요.");
			DCodeCtgr.form1.code.focus();
		} else {
			alert("상품을 등록할 카테고리를 선택하세요.");
			DCodeCtgr.form1.code.focus();
		}
		return;
	}
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

/*
	if (document.form1.up_rq_name) {
		if (document.form1.up_rq_name.value.length==0) {
			alert("요청자를 입력해주세요.");
			document.form1.up_rq_name.focus();
			return;
		}
	}
	*/
<? /* 수수료 관련 추가 jdy */?>


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
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}
	if(mode=="preview") {
		alert("미리보기 준비중....");
	} else {
		if(confirm("상품을 등록하시겠습니까?")) {
			document.form1.mode.value=mode;
			document.form1.target="processFrame";
			document.form1.submit();
		}
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


// 판매가 자동계산
function sellpriceAutoCalc ( v ) {

	if( document.form1.autoCalc.checked == true ) {

		var sell = document.form1.sellprice;
		var org = document.form1.consumerprice;
		var disc = document.form1.discountRate;

		var sellv = sell.value;
		var orgv = org.value;
		var discv = disc.value;

		discI = parseInt( 100 -( ( sellv / orgv ) * 100 ) );

		// 판매가 입력시
		if( v == 'sell' && orgv > 0 ) disc.value = discI;

		// 정상가 입력시
		if( v == 'org' && orgv > 0 ) disc.value = discI;

		// 할인율 입력시
		if( v == 'disc' ) {
			if( discv < 0 || discv > 100 ) {
				alert('할인율은 0-100 까지 입력가능합니다.');
				disc.value = 0;
			} else {
				if( orgv > 0 ) sell.value = parseInt( ( ( orgv - ( ( orgv / 100 ) * discv ) ) / 100 ) * 100 );
			}
		}

	}
}


//-->
</SCRIPT>

	<!-- 에디터용 파일 호출 -->
	<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
	<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
	<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
	<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
	<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
	<script type="text/javascript" src="/gmeditor/editor.js"></script>
	<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
	<style type="text/css">
  @import url("/gmeditor/common.css");
</style>
<!-- # 에디터용 파일 호출 -->

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<col width="190"></col>
	<col width="20"></col>
	<col width=></col>
	<col width="20"></col>
	<tr>
		<td width="190" valign="top" nowrap background="images/minishop_leftbg.gif">
			<? include ("menu.php"); ?>
		</td>
		<td width="20" nowrap></td>
		<td valign="top" style="padding-top:20px">
			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
							<tr>
								<td>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" >
										<tr>
											<td><img src="images/product_register_title.gif"></td>
										</tr>
										<tr>
											<td height="5" background="images/minishop_titlebg.gif">
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" >
										<tr>
											<td colspan="3" >
												<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
													<tr>
														<td  bgcolor="#F5F5F9" style="padding:20px">
															<table border="0" cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border="0" hspace="4">카테고리 생성은 본사 쇼핑몰에서만 관리할 수 있습니다.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border="0" hspace="4">입점사는 생성된 대분류 카테고리명을 선택하고 중>소>세분류로 구분하여 상품등록 합니다.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border="0" hspace="4">등록한 상품은 [상품진열]기능을 통해 진열할 수 있습니다.</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
				<!-- 처리할 본문 위치 시작 -->
							<tr>
								<td height="40"></td>
							</tr>
							<tr>
								<td>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
										<form name="form1" method="post" enctype="multipart/form-data">
											<input type="hidden" name="mode">
											<input type="hidden" name="code" value="">
											<input type="hidden" name="htmlmode" value='wysiwyg'>
											<input type="hidden" name="delprdtimg">
											<input type="hidden" name="option1">
											<input type="hidden" name="option2">
											<input type="hidden" name="option_price">
											<tr>
												<td><img src="images/product_register_stitle01.gif" border="0" align="absmiddle" alt="카테고리 선택"></td>
											</tr>
											<tr>
												<td height="5"></td>
											</tr>
											<tr>
												<td height="1" bgcolor="#cccccc"></td>
											</tr>
											<tr>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr height="22" align="center">
															<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">대분류</div></th>
															<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
															<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">중분류</div></th>
															<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
															<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">소분류</div></th>
															<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
															<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">세분류</div></th>
														</tr>
														<tr>
															<td height="6" colspan="7"></td>
														</tr>
														<tr>
															<td valign="top">
																<select name="code1" style="width:100%" onchange="javascript:ACodeSendIt(document.form1, this.options[this.selectedIndex]);" size="7">
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
															<td valign="top">
																<iframe name="BCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>" width="100%" height="110" scrolling="no" frameborder="no"></iframe>
																<input type="hidden" name="codeB_name" value="">
															</td>
															<td></td>
															<td valign="top">
																<iframe name="CCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,6)?>&select_code=<?=$code?>" width="100%" height="110" scrolling="no" frameborder="no"></iframe>
																<input type="hidden" name="codeC_name" value="">
															</td>
															<td></td>
															<td valign="top">
																<iframe name="DCodeCtgr" src="product_register.ctgr.php?code=<?=substr($code,0,9)?>&select_code=<?=$code?>" width="100%" height="110" scrolling="no" frameborder="no"></iframe>
																<input type="hidden" name="codeD_name" value="">
															</td>
														</tr>
														<tr>
															<td colspan="7" height="1" bgcolor="#eeeeee"></td>
														</tr>
														<tr>
															<td colspan="7" height="10">
														</tr>
														<tr>
															<td colspan="7"><img src="images/icon_dot03.gif" border="0" align="absmiddle"> <B>카테고리 선택결과</B>
																<input class="input" type="text" name="category_view" value="" style="width:100%" readonly>
															</td>
														</tr>
														<tr>
															<td colspan="7" height="5">
														</tr>
														<tr>
															<td colspan="7" height="1" bgcolor="#cccccc"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td height="40"></td>
											</tr>
											<tr>
												<td><img src="images/product_register_stitle03.gif" border="0" align="absmiddle" alt="상품정보"></td>
											</tr>
											<tr>
												<td height="5"></td>
											</tr>
											<tr>
												<td height="1" bgcolor="#cccccc"></td>
											</tr>
											<tr>
												<td>
													<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
														<col width="130"></col>
														<col width="250"></col>
														<col width="95"></col>
														<col width=></col>
													<? if(substr($_venderdata->grant_product,3,1)=="N") {
														/**수수료 관련 추가 jdy ****/
															if ($account_rule=="1" || $commission_type=="1") {  ?>
																<input type="hidden" name="display" value="N">
													<? /**수수료 관련 추가 jdy ****/?>
															<? }else { ?>

																<tr>
																	<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 상품진열</td>
																	<td colspan="3" style="padding:7,7">
																		<input type="radio" id="idx_display1" name="display" value="Y" checked>
																		<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_display1">보이기 [ON]</label>
																		<img width="50" height="0">
																		<input type="radio" id="idx_display2" name="display" value="N">
																		<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_display2">안보이기 [OFF]</label>
																	</td>
																</tr>
																<tr>
																	<td colspan="4" height="1" colspan="2" bgcolor="E7E7E7"></td>
																</tr>
															<? } ?>
														<? } else {?>
														<input type="hidden" name="display" value="N">
														<? } ?>
														<TR>
															<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>상품 구분</TD>
															<TD colspan="3">
															<script language="javascript" type="text/javascript">
															function toggleGoodsType(val){
																if(val == '2'){ // 렌탈 상품
																	$j('.rentalItemArea').css('display','block');
																	$j('.productItemArea').css('display','none');
								//									goodsTypeLocalDiv.style.display = 'block';
																}else{
																	$j('.rentalItemArea').css('display','none');
																	$j('.productItemArea').css('display','none');
								//									goodsTypeLocalDiv.style.display = 'none';
																}
																
																parent_resizeIframe('AddFrame');
															}
															
															$j(function(){
																toggleGoodsType('<?=$_data->rental?>');
															});
															</script>
																<div style="height:24px;">
																<? if(_isInt($_data->pridx)){ ?>
																	<input type="hidden" name="goodsType" value="<?=$_data->rental?>" />
																	<? echo ($_data->rental=='2')?'대여상품':'판매상품'; ?><a href="javascript:document.location.reload()">[Refresh]</a>
																<?	}else{ ?>
																	<input type=radio id="goodsType1" name="goodsType" value="1" <?=$goodsTypeSel['1']?> onclick="toggleGoodsType('1');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType1>판매상품</label> &nbsp;
																	<? if(_array($categoryRentInfo)){ ?>
																	<input type=radio id="goodsType2" name="goodsType" value="2" <?=$goodsTypeSel['2']?> onclick="toggleGoodsType('2'); "><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType2>대여상품</label><a href="javascript:document.location.reload()"><? } ?></a>
																<?	} ?>								
																</div>
															</TD>
														</TR>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font> 상품명</td>
															<td colspan="3" style="padding:7,7">
																<input class="input" name="productname" value="" maxlength="250" style="width:388" onKeyDown="chkFieldMaxLen(250)">
															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<? /* 추가 jdy */?>
														<tr>
															<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">상품홍보문구</td>
															<td colspan=3 style="padding:7,7">
																<input class="input" name="prmsg" value="" size=80 maxlength=250 style="width:388" onKeyDown="chkFieldMaxLen(250)">
															</td>
														</tr>
														<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
														<tr>
															<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 상품 등록일자</td>
															<td colspan=3 style="padding:7,7">
															<input type=checkbox id="idx_insertdate0" name=insertdate value="Y" <?=($insertdate_cook=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate0>등록 일자 고정</label>&nbsp;&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">＊체크하시면 상품수정시, 최근 상품으로 변경됩니다.</FONT>
															</td>
														</tr>
														<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
															
														<?
														$usevender = getVenderUsed();
														if($usevender[OK] == "OK"){ ?>
														<TR class="rentalItemArea">
															<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>상품소재지 및 관리</TD>
															<TD colspan="3" style="padding-left:5px;">
														<?	$commi = rentCommitionByCategory($code,$_minidata->vender);	?>															
																<div style="margin-bottom:5px;">
																	<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>   />셀프관리 (수수료  <?=number_format($commi['self'])?>%)
																	<? if($rentProduct['istrust']=='0'){ ?>
																	<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  />위탁관리 (수수료  <?=number_format($commi['main'])?>%)
																	<? }else{ ?>
																	<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> />위탁관리 요청 (수수료  <?=number_format($commi['main'])?>%)
																	<? } ?>
																</div>
																<div id="goodsTypeLocalDiv" class="rentalItemArea">	
																	<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
																		<tr>
																			<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>상품 타입</th>
																			<td style="text-align:left;padding-left:10px;">
																				<input type=radio id="itemType1" name="itemType" value="product" <?=$itemTypeSel['product']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label> &nbsp;
																				<input type=radio id="itemType2" name="itemType" value="location" <?=$itemTypeSel['location']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label> &nbsp;
																			</td>
																			<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
																			<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>가격구분</th>
																			<td style="text-align:left;padding:0px 10px;">
																				<? switch($categoryRentInfo['pricetype']){
																						case 'time': echo '시간단위 요금'; break;
																						case 'day': echo '하루(24시간)단위 요금'; break;
																						case 'checkout': echo '숙박제(오후2시~오전11시) 요금'; break;
																						default: echo '오류'; break;
																				} ?>
																			</td>
																			<? } ?>									
																			<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>성수기사용</th>
																			<td style="text-align:left;padding:0px 10px;">
																				<? echo ($categoryRentInfo['useseason'] == '1')?'성수기 사용':'사용안함';?>
																			</td>																
																		</tr>
																	</table>
															
																	<div style="margin:10px 0px;overflow:hidden;">
																		<h6 style="float:left;padding-top:5px;font-size:13px; margin:0px;font-weight:700;letter-spacing:-1px;">* 상품 출고지 및 대여 장소 선택</h6>
																		<div style="float:right;">
																			<input type="button" value="예약/렌탈 현황보기" onclick="bookingSchedulePop(<?=$_data->pridx?>);">
																			<input type="button" value="정비입고" onclick="bookingRepair(<?=$_data->pridx?>);">															
																		</div>				
																	</div>
															<?
																	// 대여 출고지 정보 리스트	
																	$value = array("display"=>1,'vender'=>(($rentProduct['istrust'] == '1')?$_minidata->vender:0)); // 노출 만 표시
																	$localList = rentLocalList( $value );
																	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
															?>
																	<!-- 리스트 --->			
																	<table border="0" cellpadding="0" cellspacing="0" class="tableBase" style="clear:both">
																		<tr align="center">
																			<th style="width:60px;" class="firstTh">지역코드</th>
																			<th style="width:60px;">타입</th>
																			<th style="width:200px;">지명</th>
																			<th style="width:700px">주소</th>
																			<th style="width:40px;">선택</th>
																		</tr>
																		<tr>
																			<td colspan="4" align="center" class="firstTd">출고지 정보 없음</td>
																			<td align="center"><input type="radio" value="0" name="location" <? if(!_isInt($rentProduct['location'])) echo 'checked="checked"';?> /></td>
																		</tr>
																		<? foreach ( $localList as $k=>$v ) { ?>
																		<tr>
																			<td class="firstTd" align="center"><?=$v['location']?></td>
																			<td align="center"><?=rentProduct::locationType($v['type'])?></td>
																			<td style="padding-left:10px;"><?=$v['title']?></td>
																			<td style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
																			<td align="center"><input type="radio" value="<?=$v['location']?>" name="location" <? if($rentProduct['location'] == $v['location']) echo 'checked="checked"';?> /></td>
																		</tr>
																		<? } ?>
																	</table>			
																	<span style="color:#ec2f36; font-weight:bold">대여상품 옵션은 상품 정보 최초 생성후 수정을 통해서 처리 가능합니다.</span>																																		
																</div>																
															</TD>
														</TR>
														<TR class="rentalItemArea">
															<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
														</TR>
														<TR class="rentalItemArea">
															<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">노출아이디선택</TD>
															<TD class="td_con1" colspan="3">
																<? if($rentProduct['istrust'] == '1') echo '본인';
																else{ ?>
																<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />본인
																<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />잠깐본점
																<?	}?>
															</TD>
														</TR>
														<TR class="rentalItemArea">
															<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
														</TR>
														<TR class="rentalItemArea">
															<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">미니홈노출</TD>
															<TD class="td_con1" colspan="3">
															<? if($rentProduct['istrust'] == '1') echo '예';
															else{ ?>
															<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />예
															<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />아니오
														<?	}?>
															</TD>
														</TR>
														<TR class="rentalItemArea">
															<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
														</TR>		
														<?  } ?>													
<? /*

														<!-- 예약 판매 상품 정보 -->
														<tr>
															<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 예약 판매 상품</td>
															<td colspan=3 style="padding:7,7">
																<input type=checkbox id="reservation" name="reservation" value="Y" onchange="reservationDiv.style.display=(this.checked==true?'block':'none');">
																<label style='cursor:hand;' onmouseover="style.reservation='underline'" onmouseout="style.reservation='none'" for=reservation>예약 판매 상품 등록</label>&nbsp;<span class="font_orange">
																<DIV style="display:none;" id="reservationDiv">
																	배송 시작일 : <input type=text name=reservationDate value="" size=12 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
																</DIV>
															</td>
														</tr>
														<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>*/?>


														<? /* 추가 jdy */?>
														<tr>
															<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> 혜택 및 구매제한</td>
															<td colspan=3 style="padding:7,7">
															<? if ($coupon_use=="1") { ?>
															<input type=checkbox id="idx_etcapply_coupon" name=etcapply_coupon value="Y" <?=($_data->etcapply_coupon=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_coupon>할인쿠폰 적용불가</label>
															&nbsp;&nbsp;&nbsp;
															<? } ?>

															<? if ($reserve_use=="1") { ?>
															<input type=checkbox id="idx_etcapply_reserve" name=etcapply_reserve value="Y" <?=($_data->etcapply_reserve=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_reserve>적립금적용불가</label>
															&nbsp;&nbsp;&nbsp;
															<? } ?>
															<input type=checkbox id="idx_etcapply_gift" name=etcapply_gift value="Y" <?=($_data->etcapply_gift=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_gift>구매사은품적용불가</label>
															<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($_data->etcapply_return=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>교환및환불 불가</label>
															<input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>현금결제만 사용하기</label> <font style="color:#2A97A7;font-size:8pt">(여러 상품과 함께 구매시 결제는 현금결제로만 진행됩니다.)</FONT>
															</td>
														</tr>
														<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
														<? /* 추가 jdy */?>

														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font> 판매가격</td>
															<td colspan=3 style="padding:7,7">

																판매가 : <input name=sellprice value="<?=(int)(strlen($_data->sellprice)>0?$_data->sellprice:"0")?>" size=16 maxlength=10 class="input" <?=($_data->assembleuse=="Y"?"disabled style='background:#C0C0C0'":"")?> style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('sell');" onfocus="sellpriceAutoCalc('sell');">원
																=
																정상가 : <input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('org');" onfocus="sellpriceAutoCalc('org');" >원
																-
																할인율 : <input name=discountRate value="<?=(int)(strlen($_data->discountRate)>0?$_data->discountRate:"0")?>" size=3 maxlength=3 class="input" style="text-align:center; font-weight:bold; width:40px;" onkeyup="sellpriceAutoCalc('disc');">%
																(<input type="checkbox" id="autoCalc" checked><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="autoCalc">자동계산</label>)

																<br><span class="font_orange">* 정상가 <strike>5,000</strike>로 표기됨, 0 입력시 표기안됨.&nbsp;</span>

															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>

														<? /****** 수수료 관련 수정 jdy ************/?>
														<? if ($account_rule=="1" || $commission_type=="1") {

																$adjust_title = "개별 수수료";
																if($account_rule) $adjust_title = "개별 공급가";
															?>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font> <?= $adjust_title ?></td>
															<td colspan="3" style="padding:7,7">
																<? if ($account_rule=="1") { ?>
																	<input type="text" size="10" class="input" name="up_rq_cost" id="up_rq_cost"/> 원 <font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;"> (상품 공급가를 입력해주세요.)</font>
																	&nbsp;&nbsp;&nbsp;요청자 <input type="text" size="10" class="input" name="up_rq_name" id="up_rq_name"/>
																	<br/>
																	<font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* 수수료 = 판매가 - 승인상품공급가</font>
																<? }else{

																	 if ($commission_type=="1") {
																		?>
																		<input type="text" size="10" class="input" name="up_rq_com" id="up_rq_com"/> %
																		&nbsp;&nbsp;&nbsp;요청자 <input type="text" size="10" class="input" name="up_rq_name" id="up_rq_name"/>
																		<br/>
																		<font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* 수수료를 입력해주세요. 관리자 승인 후 적용됩니다.</font>
																<?		}

																	} ?>

																<br/><font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* 수수료 승인 전에는 상품을 노출시킬 수 없습니다.</font>
															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<? } ?>
														<? /****** 수수료 관련 수정 jdy ************/?>
														<? /************ 적립금사용 여부 jdy ************/?>
														<? if ($reserve_use=="1") { ?>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 적립금(률)</td>
															<td style="padding:7,7" colspan="3">
																<input class="input" name="reserve" value="" size="18" maxlength="6" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);">
																<select name="reservetype" style="width:77;font-size:8pt;margin-left:1px;" onchange="chkFieldMaxLenFunc(this.form,this.value);">
																	<option value="N" selected>적립금(￦)</option>
																	<option value="Y">적립률(%)</option>
																</select>
																<br>
																<font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* 적립률은 소수점 둘째자리까지 입력 가능합니다.<br>
																* 적립률에 대한 적립 금액 소수점 자리는 반올림.</span></td>
														</tr>
														<? }else{ ?>
															<input type="hidden" name=reserve value=""/>
														<? } ?>
														<? /************ 적립금사용 여부 jdy ************/?>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 구입원가</td>
															<td style="padding:7,7" colspan="3">
																<input class="input" name="buyprice" value="" size="18" maxlength="10">
															</td>
														</tr>

														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 제조원</td>
															<td style="padding:7,7">
																<input class="input" name="production" value="" size="18" maxlength="20" onKeyDown="chkFieldMaxLen(50)">
																&nbsp;<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 원산지</td>
															<td style="padding:7,7">
																<input class="input" name="madein" value="" size="18" maxlength="20" onKeyDown="chkFieldMaxLen(30)">
																&nbsp;<a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 브랜드</td>
															<td style="padding:7,7">
																<input class="input" name="brandname" value="" size="18" maxlength="40" onKeyDown="chkFieldMaxLen(50)">
																&nbsp;<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" align="absmiddle"></a><br>
																<font style="color:#2A97A7;font-size:8pt">※ 브랜드를 직접 입력시에도 등록됩니다.</font></td>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 모델명</td>
															<td style="padding:7,7">
																<input class="input" name="model" value="" size="18" maxlength="40" onKeyDown="chkFieldMaxLen(50)">
																&nbsp;<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 진열코드</td>
															<td style="padding:7,7" colspan="3">
																<input class="input" name="selfcode" value="" size="18" maxlength="20" onKeyDown="chkFieldMaxLen(20)">
																<br>
																<font style="color:#2A97A7;font-size:8pt">* 쇼핑몰에서 자동으로 발급되는 상품코드와는 별개로 운영상 필요한 자체상품코드를 입력해 주세요.</font></td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 출시일</td>
															<td style="padding:7,7" colspan="3">
																<input class="input" name="opendate" value="" size="18" maxlength="8">
																&nbsp;&nbsp;예)
																<?=DATE("Ymd")?>
																(출시년월일)<br>
																<font style="color:#2A97A7;font-size:8pt">* 가격비교 페이지 등 제휴업체 관련 노출시 사용됩니다.<br>
																* 잘못된 출시일 지정으로 인한 문제는 상점에서 책임지셔야 됩니다.</font></td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 수량</td>
															<td colspan="3" style="padding:7,7">
																<?
													$checkquantity="F";

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
													echo ": <input type=text class=input  class=input name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\">개";

													echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
							?>
															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 최소주문한도</td>
															<td style="padding:7,7">
																<input type="text" class="input"  name="miniq" value="1" size="5" maxlength="5">
																개 이상</td>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 최대주문한도</td>
															<td style="padding:7,7">
																<input type="radio" id="idx_checkmaxq1" name="checkmaxq" value="A" checked onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';">
																<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkmaxq1">무제한</label>
																&nbsp;
																<input type="radio" id="idx_checkmaxq2" name="checkmaxq" value="B" onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';">
																<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkmaxq2">수량</label>
																:
																<input class="input" name="maxq" size="5" maxlength="5" value="">
																개 이하
																<script>
													if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
													else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
													</script>
															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 개별배송비</td>
															<td colspan="3" style="padding:7,7">
																<table border="0" cellpadding="0" cellspacing="0" width="100%">
																	<tr>
																		<td>
																			<input type="radio" id="idx_deliprtype0" name="deli" value="H" checked onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype0">기본 배송비 <b>유지</b></label>
																			&nbsp;&nbsp;&nbsp;&nbsp;
																			<input type="radio" id="idx_deliprtype2" name="deli" value="F" onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype2">개별 배송비 <b><font color="#0000FF">무료</font></b></label>
																			&nbsp;&nbsp;&nbsp;&nbsp;
																			<input type="radio" id="idx_deliprtype1" name="deli" value="G" onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype1">개별 배송비 <b><font color="#38A422">착불</font></b></label>
																		</td>
																	</tr>
																	<tr>
																		<td height="5"></td>
																	</tr>
																	<tr>
																		<td>
																			<input type="radio" id="idx_deliprtype3" name="deli" value="N" onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype3">개별 배송비 <b><font color="#FF0000">유료</font></b>
																				<input type="text" class="input"  name="deli_price_value1" value="" size="6" maxlength="6" disabled style='background:silver'>
																				원</label>
																			<br>
																			<input type="radio" id="idx_deliprtype4" name="deli" value="Y" onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype4">개별 배송비 <b><font color="#FF0000">유료</font></b>
																				<input type="text" class="input"  name="deli_price_value2" value="" size="6" maxlength="6" disabled style='background:silver'>
																				원 (구매수 대비 개별 배송비 증가 : <FONT COLOR="#FF0000"><B>상품구매수×개별 배송비</B></font>)</label>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 상품노출등급</td>
															<td colspan="3" style="padding:7,7">
																<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																	<tr>
																		<td>
																			<input type="radio" id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" checked>
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">상품노출등급 미지정</label>
																			&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">* 상품노출등급 미지정할 경우 모든 비회원, 회원에게 노출됩니다.</font><br>
																			<input type="radio" id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">상품노출등급 지정</label>
																		</td>
																	</tr>
																	<tr>
																		<td height="5"></td>
																	</tr>
																	<tr id="group_checkidx" style='display:none;'>
																		<td>
																			<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																				<tr>
																					<td bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
																						<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																							<tr>
																								<?
															$sqlgrp = "SELECT group_code,group_name FROM tblmembergroup ";
															$resultgrp = mysql_query($sqlgrp,get_db_conn());
															$grpcnt=0;
															while($rowgrp = mysql_fetch_object($resultgrp)){
																if($grpcnt!=0 && $grpcnt%4==0) {
																	echo "</tr>\n<tr>\n";
																}
																echo "<td width=\"25%\" style=\"padding:3px;\"><input type=checkbox id=\"group_code_idx".$grpcnt."\" name=\"group_code[]\" value=\"".$rowgrp->group_code."\"> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"group_code_idx".$grpcnt."\">".$rowgrp->group_name."</label></td>\n";
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
														<tr>
															<td height="1" colspan="4" bgcolor="E7E7E7"></td>
														</tr>
														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px;"> 상품정보고시</td>
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
																		<td class="td_con1" style="padding:8px 0px 8px 0px;">
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
																				.dtitleTd{ padding:0px 0px 0px 10px; background-color:#f5f5f5; }
																				.daccTd{ padding:8px 0px 8px 10px; }
																				.dbtnTd{ padding:10px 0px 10px 0px; }
																				.dtitleInput{ width:96%; border:1px solid #ccc; font-family:돋움; letter-spacing:-1px; }
																				.ditemTextarea{ width:98%; line-height:18px;}
																				.font_orange{color:#ff6600; letter-spacing:-0.5px; line-height:120%;}
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
																			//$detialItems = _getProductDetails($_data->pridx);
																			?>
																			<table width="98%" border="0" cellpadding="0" cellspacing="0" id="detailTable" style="margin:0px 10px 0px 15px; display:<?=(count($detialItems)>0)?'':'none'?>; border-bottom:1px solid #dddddd">
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

														<tr>
																<td height="1" colspan="4" bgcolor="E7E7E7"></td>
															</tr>

														<tr>
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 사용자 정의 스펙</TD>
															<td colspan="3" style="padding:7,7">
																<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																	<col width="180"></col>
																	<col width=""></col>
																	<tr>
																		<td colspan="2">
																			<input type="radio" id="idx_userspec1" name="userspec" onclick="userspec_change('N');" value="N" checked>
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_userspec1">사용자 정의 스펙 사용안함</label>
																			&nbsp;&nbsp;&nbsp;&nbsp;
																			<input type="radio" id="idx_userspec0" name="userspec" onclick="userspec_change('Y');" value="Y">
																			<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_userspec0">사용자 정의 스펙 사용함</label>
																		</td>
																	</tr>
																	<tr>
																		<td height="5"></td>
																	</tr>
																		<tr id="userspecidx" style='display:none;'>
																			<td valign="top" bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
																				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
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
																							<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
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
																							<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																								<col width="20"></col>
																								<col width=""></col>
																								<? for($i=0; $i<$userspec_cnt; $i++) {?>
																								<tr>
																									<td style="padding:5px;padding-bottom:0px;padding-left:7px;padding-right:2px;" align="center"><?=str_pad(($i+1), 2, "0", STR_PAD_LEFT);?>.</td>
																									<td style="padding:5px;padding-bottom:0px;padding-left:0px;"><input class="input" name="specname[]" value="" size="30" maxlength="30" style="width:100%;"></td>
																								</tr>
																								<? }?>
																							</table>
																						</td>
																					</tr>
																					<tr>
																						<td height="10"></td>
																					</tr>
																				</table>
																			</td>
																			<td valign="top" bgcolor="#F1FFEF" style="border:2px #57B54A solid;border-left:1px #57B54A solide;">
																				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
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
																							<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
																								<tr>
																									<td height="1" bgcolor="#DADADA"></td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																					<tr>
																						<td height="5"></td>
																					</tr>
																					<? for($i=0; $i<$userspec_cnt; $i++) {?>
																					<tr>
																						<td style="padding:5px;padding-bottom:0px;">
																							<input class="input" name="specvalue[]" value="" size="50" maxlength="100" style="width:100%;">
																						</td>
																					</tr>
																					<? }?>
																					<tr>
																						<td height="10"></td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																	</table>
																</TD>
															</tr>
															<tr>
																<td height="1" colspan="4" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 검색어</td>
																<td colspan="3" style="padding:7,7">
																	<input class="input" name="keyword" value="" size="80" maxlength="100" onKeyDown="chkFieldMaxLen(100)">
																</td>
															</tr>
															<tr>
																<td height="1" colspan="4" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 상품 특이사항</td>
																<td colspan="3" style="padding:7,7">
																	<input class="input" name="addcode" value="" size="43" maxlength="200" onKeyDown="chkFieldMaxLen(200)">
																	&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">(예: 향수는 용량표시, TV는 17인치등)</font> </td>
															</tr>
															<tr>
																<td height="1" colspan="4" bgcolor="E7E7E7"></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="40"></td>
												</tr>
												<tr>
													<td><img src="images/product_register_stitle04.gif" border="0" align="absmiddle" alt="사진정보"></td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#cccccc"></td>
												</tr>
												<tr>
													<td>
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
															<col width="130"></col>
															<col width=></col>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 큰이미지</td>
																<td style="padding:7,7">
																	<input type="file" name="userfile" class="button" style="width=300px" onchange="document.getElementById('size_checker').src=this.value;">
																	<font style="color:#2A97A7;font-size:8pt">(권장이미지 : 550X550)</font> <br>
																	<input type="checkbox" id="idx_imgcheck1" name="imgcheck" value="Y">
																	<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_imgcheck1"><font color="#003399">큰 이미지로 중간/작은 이미지 자동생성 (이미지 권장 사이즈로 변경)</font></label>
																</td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 중간이미지</td>
																<td style="padding:7,7">
																	<input type="file" name="userfile2" class="button" style="width=300px" onchange="document.getElementById('size_checker2').src = this.value;" >
																	<font style="color:#2A97A7;font-size:8pt">(권장이미지 : 300X300)</font> </td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 작은이미지</td>
																<td style="padding:7,7">
																	<input type="file" name="userfile3" class="button" style="width=300px" onchange="document.getElementById('size_checker3').src = this.value;" >
																	<font style="color:#2A97A7;font-size:8pt">(권장이미지 : 130X130)</font>
																	<input type="hidden" name="setcolor" value="<?=$setcolor?>">
																	<BR>
																	<table border="0" cellpadding="0" cellspacing="0">
																		<tr>
																			<td>
																				<input type="checkbox" name="imgborder" value="Y" <?=(($imgborder)=="Y"?"checked":"")?>>
																			</td>
																			<td style="padding-top:4px"><font color="#003399">신규 등록시,&nbsp;(&nbsp;</td>
																			<td width="10" align="center" valign="middle">
																				<div id="ColorPreview" style="background-color: #<?=$setcolor?>;height: 10px; width: 15px"></div>
																			</td>
																			<td style="padding-top:4px"><font color="#003399">&nbsp;)&nbsp;로 상품 테두리선 생성!&nbsp;&nbsp;다른 색상선택-></font></td>
																			<td><a href="JavaScript:SelectColor()"><img src="images/ed_color_bg.gif" align="absmiddle" border="0"></a></td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 모바일샵 이미지</td>
																<td style="padding:7,7">
																	<input type="file" name="wideimage" class="button" style="width=300px" onchange="document.getElementById('size_checker3').src = this.value;" >
																	<font style="color:#2A97A7;font-size:8pt">(권장이미지 : 400X180)</font>
																	<p style="color:#ff6600">
																		* 모바일샵 메인 디스플레이 타입 중 리스트타입 전용 이미지를 첨부하는 기능입니다.<br/>
																		* 해당 이미지를 첨부하지 않은 상태에서 모바일샵 메인 디스플레이 타입을 리스트로 적용 할 경우 상품 이미지가 노출 되지 않습니다.<br/>
																		* 리스트이미지 변경을 원하실 경우 재 첨부를 하신 뒤 수정 하시면 변경 됩니다.<br/>
																	</p>
																</td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<td><img src="images/product_register_stitle05.gif" border="0" align="absmiddle" alt="상품상세정보">
														<? if($predit_type=="Y" && false){?>
														&nbsp;&nbsp;
														<input type="radio" id="idx_checkedit1" name="checkedit" checked onclick="JavaScript:htmlsetmode('wysiwyg',this)">
														<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkedit1">웹편집기로 입력하기(권장)</label>
														&nbsp;&nbsp;
														<input type="radio" id="idx_checkedit2" name="checkedit" onclick="JavaScript:htmlsetmode('textedit',this);">
														<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkedit2">직접 HTML로 입력하기</label>
														<? } ?>
													</td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#cccccc"></td>
												</tr>
												<tr>
													<td>
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
															<tr>
																<td>
																	<textarea wrap="off" style="width:100%; height:300" name="content" lang="ej-editor1"></textarea>
																</td>
															</tr>
															<tr>
																<td> <img id="size_checker" style="display:none;"> <img id="size_checker2" style="display:none;"> <img id="size_checker3" style="display:none;"> </td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<td><img src="images/product_register_stitle06.gif" border="0" align="absmiddle" alt="추가정보"></td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#cccccc"></td>
												</tr>
												<tr>
													<td>
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
															<col width="130"></col>
															<col width=></col>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 옵션정보</td>
																<td style="padding:7,7">
																	<input type="radio" id="idx_searchtype0" name="searchtype" onclick="ViewLayer('layer0')" value="0" checked>
																	<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype0">옵션정보 없음</label>
																	<img width="30" height="0">
																	<input type="radio" id="idx_searchtype1" name="searchtype" onclick="ViewLayer('layer1');alert('옵션1, 옵션2는 최대 10개로 각 옵션별 수량조절이 가능하게 됩니다.\n\n수정시 기존의 그이상의 옵션들은 삭제됩니다.');" value="1">
																	<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype1">재고관리 상품 옵션</label>
																	<img width="30" height="0">
																	<input type="radio" id="idx_searchtype2" name="searchtype" onclick="ViewLayer('layer2')" value="2">
																	<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype2">상품 옵션 무제한 등록</label>
																	</td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td colspan="2">
																	<div id="layer0" style="margin-left:0;display:hide; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;"> </div>
																	<div id="layer1" style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																			<col width="130"></col>
																			<col width=></col>
																			<tr>
																				<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px">옵션제목 입력</td>
																				<td style="padding:7,7"> <font color="#FF6000"><b>옵션1 : </b></font>
																					<input name="option1_name" value="" size="20" maxlength="20">
																					<img width="40" height="0"> <font color="#128C02"><b>옵션2 : </b></font>
																					<input name="option2_name" value="" size="20" maxlength="20">
																				</td>
																			</tr>
																			<tr>
																				<td height="1" colspan="2" bgcolor="E7E7E7"></td>
																			</tr>
																			<tr>
																				<td colspan="2" height="5"></td>
																			</tr>
																		</table>
																		<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" width="100%" style="table-layout:fixed">
																			<col width="14%"></col>
																			<col width="2"></col>
																			<col width="14%"></col>
																			<col width="2"></col>
																			<col width=></col>
																			<tr>
																				<td bgcolor="#FFF7F0">
																					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																						<col width="2"></col>
																						<col width="2"></col>
																						<col width=></col>
																						<col width="2"></col>
																						<col width="2"></col>
																						<tr bgcolor="#FF7100" height="2">
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																						</tr>
																						<tr height="50">
																							<td bgcolor="#FF7100" rowspan="25"></td>
																							<td rowspan="25"></td>
																							<td align="center"><b>옵션1</b></td>
																							<td rowspan="25"></td>
																							<td bgcolor="#FF7100" rowspan="25"></td>
																						</tr>
																						<tr height="1" bgcolor="#DADADA">
																							<td></td>
																						</tr>
																						<tr height="1">
																							<td></td>
																						</tr>
																						<?
																						for($i=1;$i<=10;$i++){
																							if($i==6) echo "<tr height=5><td></td></tr>";
																							echo "<tr height=7><td></td></tr>";
																							echo "<tr height=19><td align=center><input type=text class=input  name=optname1 value=\"\" size=12></td></tr>";
																						}
																						echo "<tr height=2><td></td></tr>";
																						echo "<tr height=2><td colspan=5 bgcolor=#FF7100></td></tr>";
																						?>
																					</table>
																				</td>
																				<td></td>
																				<td bgcolor="#F2F8FD">
																					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																						<col width="2"></col>
																						<col width="2"></col>
																						<col width=></col>
																						<col width="2"></col>
																						<col width="2"></col>
																						<tr bgcolor="#0071C3" height="2">
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																						</tr>
																						<tr height="50">
																							<td bgcolor="#0071C3" rowspan="25"></td>
																							<td rowspan="25"></td>
																							<td align="center"><b>가격</b></td>
																							<td rowspan="25"></td>
																							<td bgcolor="#0071C3" rowspan="25"></td>
																						</tr>
																						<tr height="1" bgcolor="#DADADA">
																							<td></td>
																						</tr>
																						<tr height="1">
																							<td></td>
																						</tr>
																						<?
																						for($i=0;$i<10;$i++){
																							if($i==5) echo "<tr height=5><td></td></tr>";
																							echo "<tr height=7><td></td></tr>";
																							echo "<tr height=19><td align=center><input type=text class=input  name=optprice size=12 onkeyup=\"strnumkeyup(this)\"></td></tr>";
																						}
																						echo "<tr height=2><td></td></tr>";
																						echo "<tr height=2><td colspan=5 bgcolor=#0071C3></td></tr>";
																						?>
																					</table>
																				</td>
																				<td></td>
																				<td colspan="2" bgcolor="#FFFFFF" valign="top">
																					<table border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed">
																						<col width="2"></col>
																						<col width="2"></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width=></col>
																						<col width="2"></col>
																						<col width="2"></col>
																						<tr bgcolor="#57B54A" height="2">
																							<td rowspan="4"></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td></td>
																							<td rowspan="4"></td>
																						</tr>
																						<tr height="27" bgcolor="#F1FFEF">
																							<td colspan="12" align="center"><b>옵션2</b></td>
																						</tr>
																						<tr height="19" bgcolor="#F1FFEF">
																							<td></td>
																							<?
														for($i=1;$i<=10;$i++){
															echo "<td align=center width=20%><input type=text class=input  name=optname2 value=\"\" size=12></td>";
														}
						?>
																							<td></td>
																						</tr>
																						<tr height="4" bgcolor="#F1FFEF">
																							<td colspan="12"></td>
																						</tr>
																						<tr height="2" bgcolor="#57B54A">
																							<td colspan="14"></td>
																						</tr>
																						<tr height="7">
																							<td colspan="2" rowspan="23"></td>
																							<td colspan="10"></td>
																							<td colspan="2" rowspan="23"></td>
																						</tr>
																						<?
													for($i=0;$i<10;$i++){
														if($i!=0 && $i!=10) echo "<tr><td colspan=10 height=7></td></tr>";
														else if($i==10) echo "<tr><td colspan=10 height=6></td></tr>
																			<tr><td colspan=10 height=1 bgcolor=#DADADA></td></tr>
																			<tr><td colspan=10 height=6></td></tr>";
														echo "<tr height=19>";
														for($j=0;$j<10;$j++){
															echo "<td align=center><input type=text class=input  name=optnumvalue[".$j."][".$i."] size=12 maxlength=3 onkeyup=\"strnumkeyup(this)\"></td>\n";
														}
														echo "</tr>";
													}
						?>
																					</table>
																				</td>
																			</tr>
																		</table>
																	</div>
																	<div id="layer2" style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
																		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																			<col width="130"></col>
																			<col width=></col>
																			<tr>
																				<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px">옵션1</td>
																				<td style="padding:7,7">
																					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																						<col width="40"></col>
																						<col width=></col>
																						<tr>
																							<td> 속성명 </td>
																							<td style="padding-left:5">
																								<input class="input" name="toptname1" value="" size="30" maxlength="20">
																								&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">색상 or 사이즈 or 용량등</font> </td>
																						</tr>
																						<tr>
																							<td> 속성 </td>
																							<td style="padding-left:5">
																								<input class="input" name="toption1" value="" maxlength="230" style="width:100%">
																							</td>
																						</tr>
																						<tr>
																							<td colspan="2" width="100%" style="padding-left:3"> <font style="color:#2A97A7;font-size:8pt"> 예) 빨강,파랑,노랑 <br>
																								- 속성명에 색상을 입력하고 속성에 빨강,노랑을 입력하면 <br>
																								<img width="9" height="0">사용자는 빨강,노랑중 하나를 선택할 수 있습니다. <br>
																								- 속성에는 빈칸없이 콤마(,)로 구분입력 </font> </td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																			<tr>
																				<td height="1" colspan="2" bgcolor="E7E7E7"></td>
																			</tr>
																			<tr>
																				<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px">옵션1에 따른 가격</td>
																				<td style="padding:7,7">
																					<input class="input" name="toption_price" value="" maxlength="250" style="width:100%">
																					<BR style="line-height:2pt">
																					<font style="color:#2A97A7;font-size:8pt"> 예) 1000,2000,3000 <br>
																					- 옵션1 의 속성과 일대일로 매치되는 가격, 옵션에 따른 가격변동시 입력 <br>
																					- 옵션1에따른 가격을 입력하시면 판매가격을 무시합니다. </font> </td>
																			</tr>
																			<tr>
																				<td height="1" colspan="2" bgcolor="E7E7E7"></td>
																			</tr>
																			<tr>
																				<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px">옵션2</td>
																				<td style="padding:7,7">
																					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																						<col width="40"></col>
																						<col width=></col>
																						<tr>
																							<td> 속성명 </td>
																							<td style="padding-left:5">
																								<input class="input" name="toptname2" value="" size="30" maxlength="20">
																							</td>
																						</tr>
																						<tr>
																							<td> 속성 </td>
																							<td style="padding-left:5">
																								<input class="input" name="toption2" value="" maxlength="230" style="width:100%">
																							</td>
																						</tr>
																						<tr>
																							<td colspan="2" width="100%" style="padding-left:3"> <font style="color:#2A97A7;font-size:8pt"> - 옵션1과 사용법은 같으나 "<B>옵션1에 따른 가격</B>"과는 무관합니다. </font> </td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																			<tr>
																				<td height="1" colspan="2" bgcolor="E7E7E7"></td>
																			</tr>
																		</table>
																	</div>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<td>
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
															<col width="130"></col>
															<col width=></col>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 아이콘 꾸미기</td>
																<td style="padding:7,7">
																	<table border="0" cellpadding="0" cellspacing="0" width="100%">
																		<?
												$iconarray = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28");
												$totaliconnum = 0;
												for($i=0;$i<count($iconarray);$i++) {
													if($i%7==0) echo "<tr height=25>";
													echo "<td width=14%><input type=checkbox name=icon onclick=CheckChoiceIcon('".$totaliconnum."') value=\"".$iconarray[$i]."\" ";
													if($iconvalue2[$iconarray[$i]]=="Y") echo "checked";
													echo "><img src=\"".$Dir."images/common/icon".$iconarray[$i].".gif\" border=0 align=absmiddle></td>\n";
													if($i%7==6) echo "</tr>";
													$totaliconnum++;
												}
						?>
																	</table>
																</td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>
															<tr>
																<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> 배송/쇼환/환불정보</td>
																<td style="padding:7,7">
																	<input type="checkbox" id="idx_deliinfono1" name="deliinfono" value="Y">
																	<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliinfono1">배송/교환/환불정보 노출안함</label>
																	<font style="color:#2A97A7;font-size:8pt">(상세화면 하단에 배송/교환/환불정보가 노출안됨)</font> </td>
															</tr>
															<tr>
																<td height="1" colspan="2" bgcolor="E7E7E7"></td>
															</tr>

														</table>
													</td>
												</tr>
												<tr>
													<td height="20"></td>
												</tr>
												<tr>
													<td align="center">
														<!--A HREF="javascript:formSubmit('preview')"><img src="images/btn_preview01.gif" border="0"></A>
											&nbsp;-->
														<A HREF="javascript:formSubmit('insert')"><img src="images/btn_regist01.gif" border="0"></A> </td>
												</tr>
												<input type="hidden" name="iconnum" value='<?=$totaliconnum?>'>
												<input type="hidden" name="iconvalue">
											</form>
										</table>
										<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling="no" frameborder="no"></iframe>
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
<?
if ($predit_type=="Y" && false) {
?>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script language="JavaScript">
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
<? include "copyright.php"; ?>
