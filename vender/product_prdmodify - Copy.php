<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/admin_more.php");

if(substr($_venderdata->grant_product,1,1)!="Y") {
	echo "<html></head><body onload=\"alert('��ǰ���� ���� ������ �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');\"></body></html>";exit;
}

$userspec_cnt = 5;
$maxfilesize="2097152";
$savewideimage = $Dir.DataDir."shopimages/wideimage/"; //���̵��̹���
$mode=$_POST["mode"];
$prcode=$_POST["prcode"];
$code=substr($prcode,0,12);

// ���� ����, ������, ���� ��뿩�� ��ȸ jdy
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
// ���� ����, ������, ���� ��뿩�� ��ȸ jdy

/*
$sql = "SELECT * FROM tblproduct WHERE productcode = '".$prcode."' AND vender='".$_VenderInfo->getVidx()."' ";
*/
/****** ������ ���� ���� jdy ************/
$sql = "SELECT p.*, c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval FROM tblproduct p left join product_commission c on p.productcode=c.productcode WHERE p.productcode = '".$prcode."' AND vender='".$_VenderInfo->getVidx()."' ";
/****** ������ ���� ���� jdy ************/

$result = mysql_query($sql,get_db_conn());
if (!$_data = mysql_fetch_object($result)) {
	echo "<html></head><body onload=\"alert('�ش� ��ǰ�� �������� �ʽ��ϴ�.');";
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


//POST ������ ���� ����
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
$deliinfono=$_POST["deliinfono"];	// ���/��ȯ/ȯ������ ������� (Y)
$miniq=$_POST["miniq"];			// �ּ��ֹ�����
$maxq=$_POST["maxq"];			// �ִ��ֹ�����
$insertdate=$_POST["insertdate"];
$content=$_POST["content"];

$userspec=$_POST["userspec"];
$specname=$_POST["specname"];
$specvalue=$_POST["specvalue"];

$group_check=$_POST["group_check"];
$group_code=$_POST["group_code"];

/* ���������� �����ϰ� �߰� jdy */

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


//���� �Ǹ� ��ǰ ����
$reservation = ( $_POST["reservation"] == "Y" AND strlen($_POST["reservationDate"]) > 0 ) ? $_POST["reservationDate"] : '' ;


/* ���������� �����ϰ� �߰� jdy */

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

// �׵θ� ������ ���� �κ��� ��Ű�� ������Ų��.
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	SetCookie("imgborder","Y",0,"/".RootPath.VenderDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && $mode=="update") {
	SetCookie("imgborder","",time()-3600,"/".RootPath.VenderDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}

// ��ǰ������� ������ ���� ��Ű�� �˻��ϰ� �븩�ϰ� �������´�..
if ($_COOKIE["insertdate_cook"]=="Y" && $insertdate!="Y" && $mode=="update") {
	setCookie("insertdate_cook","",time()-3600,"/".RootPath.VenderDir);
	$insertdate_cook="";
} else if ($_COOKIE["insertdate_cook"]!="Y" && $insertdate=="Y" && $mode=="update") {
	setCookie("insertdate_cook","Y",time()+2592000,"/".RootPath.VenderDir);
	$insertdate_cook="Y";
}
// ��Ű ��

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
	echo "<html></head><body onload=\"alert('�ش� ��ǰ�̹����� �����Ͽ����ϴ�.');parent.document.iForm.submit();\"></body></html>"; exit;
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
		echo "<html></head><body onload=\"alert('�ּ��ֹ��ѵ� ������ 1�� ���� Ŀ�� �մϴ�.')\"></body></html>";exit;
	}
	if ($checkmaxq=="B" && $maxq>=1)        $etctype .= "MAXQ=".$maxq."";
	else if ($checkmaxq=="B" && $maxq<1) {
		echo "<html></head><body onload=\"alert('�ִ��ֹ��ѵ� ������ 1�� ���� Ŀ�� �մϴ�.')\"></body></html>";exit;
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

		/** ������ ���� ���� ó�� �߰� �κ� */
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
		/** #������ ���� ���� ó�� �߰� �κ� */


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
					$im2=ImageCreate($small_width,$small_height); // GIF�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG�ϰ��
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
					$im2=ImageCreate($small_width,$small_height); // GIF�ϰ��
					// Ȧ���ȼ��� ��� �������� ������� �ٲٱ�����.
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					//$color = ImageColorAllocate ($im2, 0, 0, 0);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG�ϰ��
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

		#���̵� �̹��� �߰�
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
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		if(substr($_venderdata->grant_product,3,1)=="N") {

			//������ ������ ���� ���� ���¿��� ���� �Ұ� jdy
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

		/* ���������� �����ϰ� �߰� jdy */

		$sql.= "etcapply_coupon	= '".$etcapply_coupon."', ";
		$sql.= "etcapply_reserve= '".$etcapply_reserve."', ";
		$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
		$sql.= "etcapply_return	= '".$etcapply_return."', ";

		$sql.= "productdisprice	= '".$productdisprice."', ";
		/* ���������� �����ϰ� �߰� jdy */

		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".$content."', ";

		$sql.= "rental = '".$goodsType."' ";

		$sql.= "WHERE productcode = '".$prcode."' ";
		#echo $sql; exit;
		if(mysql_query($sql,get_db_conn())) {
			// ��Ƽ ī�װ� �߰��� ���� ���� ����
			$sql = "SELECT count(*) FROM tblcategorycode WHERE productcode = '".$prcode."' ";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_result($res,0,0) < 1){
					$sql = "insert into tblcategorycode set productcode='".$prcode."',categorycode='".substr($prcode,0,12)."'";
					@mysql_query($sql,get_db_conn());
				}
			}

			// ��ǰ ���� ��� ���� �߰�
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
			// #��ǰ ���� ��� ���� �߰�

			if(strlen($brandname)>0) { // �귣�� ���� ó��
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
				$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
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


			// �뿩 ��ǰ ����
			$pridx = productcodeToPridx($prcode);
			$rentProductValue = array();
			$rentProductValue['pridx'] = $pridx;
			$rentProductValue['mangeVender'] = $_POST["mangeVender"];
			$rentProductValue['location'] = $_POST["location"];
			$rentProductValue['goodsType'] = $_POST["goodsType"];
			$rentProductValue['itemType'] = $_POST["itemType"];
			$rentProductResult = rentProductSave( $rentProductValue );


			$onload="<html></head><body onload=\"alert('��ǰ���� ������ �Ϸ�Ǿ����ϴ�.');parent.iForm.submit()\"></body></html>";

			$log_content = "## ��ǰ���� ## - �ڵ� $prcode - ��ǰ : $productname ���� : $sellprice ���� : $quantity ��Ÿ : $etctype ������ : $reserve ��¥���� : ".(($insertdate=="Y")?"Y":"N")." $display";
			$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content);
		} else {
			$onload="<html></head><body onload=\"alert('��ǰ���� ������ ������ �߻��Ͽ����ϴ�.')\"></body></html>";
		}
	} else {
		$onload="<html></head><body onload=\"alert('��ǰ�̹����� �� �뷮�� ".ceil($file_size/1024)
		."Kbyte�� 300K�� �ѽ��ϴ�.\\n\\n�ѹ��� �ø� �� �ִ� �ִ� �뷮�� 300K�Դϴ�.\\n\\n"
		."�̹����� gif�� �ƴϸ� �̹��� ������ �ٲپ� �ø��ø� �뷮�� �پ��ϴ�.')\"></body></html>";
	}
	echo $onload; exit;

//������ ���� ��û jdy
}else if($mode=="com") {

	/* ���� ������ ���� jdy */
	$up_rq_com = $_REQUEST['up_rq_com'];
	$up_rq_cost = $_REQUEST['up_rq_cost'];
	$up_rq_name = $_REQUEST['up_rq_name'];
	insertCommission($_VenderInfo->getVidx(), $prcode, $up_rq_com, $up_rq_cost, $up_rq_name, "0", "");
	/* ���� ������ ���� jdy */

	$onload="<html></head><body onload=\"alert('������ ���� ��û�� �Ϸ�Ǿ����ϴ�.');parent.iForm.submit()\"></body></html>";
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
function DeletePrdtImg(temp){
	if(confirm('�ش� �̹����� �����Ͻðڽ��ϱ�?')){
		document.cForm.mode.value="delprdtimg";
		document.cForm.delprdtimg.value=temp-1;
		document.cForm.target="processFrame";
		document.cForm.submit();
	}
}

function formSubmit(mode) {
	if (document.form1.productname.value.length==0) {
		alert("��ǰ���� �Է��ϼ���.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>100) {
		alert('�� �Է°����� ���̰� �ѱ� 50�ڱ����Դϴ�. �ٽ��ѹ� Ȯ���Ͻñ� �ٶ��ϴ�.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		alert("�Һ��ڰ����� �Է��ϼ���.");
		document.form1.consumerprice.focus();
		return;
	}
	if (isNaN(document.form1.consumerprice.value)) {
		alert("�Һ��ڰ����� ���ڷθ� �Է��ϼ���.(�޸�����)");
		document.form1.consumerprice.focus();
		return;
	}
	if (document.form1.sellprice.value.length==0) {
		alert("�ǸŰ����� �Է��ϼ���.");
		document.form1.sellprice.focus();
		return;
	}
	if (isNaN(document.form1.sellprice.value)) {
		alert("�ǸŰ����� ���ڷθ� �Է��ϼ���.(�޸�����)");
		document.form1.consumerprice.focus();
		return;
	}
	if (document.form1.reserve.value.length>0) {
		if(document.form1.reservetype.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.reserve.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.reserve.focus();
				return;
			}

			if(getPointCount(document.form1.reserve.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.reserve.focus();
				return;
			}

			if(Number(document.form1.reserve.value)>100 || Number(document.form1.reserve.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.reserve.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.reserve.focus();
				return;
			}
		}
	}
	if (document.form1.checkquantity[2].checked==true) {
		if (document.form1.quantity.value.length==0) {
			alert("������ �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		} else if (isNaN(document.form1.quantity.value)) {
			alert("������ ���ڷθ� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		} else if (parseInt(document.form1.quantity.value)<=0) {
			alert("������ 0���̻��̿��� �մϴ�.");
			document.form1.quantity.focus();
			return;
		}
	}
	miniq_obj=document.form1.miniq;
	maxq_obj=document.form1.maxq;
	if (miniq_obj.value.length>0) {
		if (isNaN(miniq_obj.value)) {
			alert ("�ּ��ֹ��ѵ��� ���ڷθ� �Է��� �ּ���.");
			miniq_obj.focus();
			return;
		}
	}
	if (document.form1.checkmaxq[1].checked==true) {
		if (maxq_obj.value.length==0) {
			alert ("�ִ��ֹ��ѵ��� ������ �Է��� �ּ���.");
			maxq_obj.focus();
			return;
		} else if (isNaN(maxq_obj.value)) {
			alert ("�ִ��ֹ��ѵ��� ������ ���ڷθ� �Է��� �ּ���.");
			maxq_obj.focus();
			return;
		}
	}
	if (miniq_obj.value.length>0 && document.form1.checkmaxq[1].checked==true && maxq_obj.value.length>0) {
		if (parseInt(miniq_obj.value) > parseInt(maxq_obj.value)) {
			alert ("�ּ��ֹ��ѵ��� �ִ��ֹ��ѵ� ���� �۾ƾ� �մϴ�.");
			miniq_obj.focus();
			return;
		}
	}
	if(document.form1.deli[3].checked==true || document.form1.deli[4].checked==true) {
		if(document.form1.deli[3].checked==true)
		{
			if (document.form1.deli_price_value1.value.length==0) {
				alert("������ۺ� �Է��ϼ���.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value1.value)) {
				alert("������ۺ�� ���ڷθ� �Է��ϼ���.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value1.value)<=0) {
				alert("������ۺ�� 0�� �̻� �Է��ϼž� �մϴ�.");
				document.form1.deli_price_value1.focus();
				return;
			}
		}
		else
		{
			if (document.form1.deli_price_value2.value.length==0) {
				alert("������ۺ� �Է��ϼ���.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value2.value)) {
				alert("������ۺ�� ���ڷθ� �Է��ϼ���.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value2.value)<=0) {
				alert("������ۺ�� 0�� �̻� �Է��ϼž� �մϴ�.");
				document.form1.deli_price_value2.focus();
				return;
			}
		}
	}
	if(shop=="layer0") {

	} else if(shop=="layer1"){
		optnum1=0;
		optnum2=0;

		//�ɼ�1 �׸�
		document.form1.option1.value="";
		for(i=0;i<10;i++){
			if(document.form1.optname1[i].value.length>0) {
				document.form1.option1.value+=document.form1.optname1[i].value+",";
				optnum1++;
			}
		}

		//�ɼ�1 ���� �˻� (�ɼ�1 �׸��� NULL�� �ƴϸ�)
		if((document.form1.option1.value.length!=0 && document.form1.option1_name.value.length==0)
		|| (document.form1.option1.value.length==0 && document.form1.option1_name.value.length!=0)){
			alert('�� �ɼǺ� �����Է°� [�ɼ�����]�� Ȯ�����ּ���!');
			if(document.form1.option1_name.value.length==0) {
				document.form1.option1_name.focus();
			} else {
				document.form1.optname1[0].focus();
			}
			return;
		}

		//�ɼ�2 �׸�
		document.form1.option2.value="";
		for(i=0;i<10;i++){
			if(document.form1.optname2[i].value.length>0) {
				document.form1.option2.value+=document.form1.optname2[i].value+",";
				optnum2++;
			}
		}

		//�ɼ�2 ���� �˻� (�ɼ�2 �׸��� NULL�� �ƴϸ�)
		if((document.form1.option2.value.length!=0 && document.form1.option2_name.value.length==0)
		|| (document.form1.option2.value.length==0 && document.form1.option2_name.value.length!=0)){
			alert('�� �ɼǺ� �����Է°� [�ɼ�����]�� Ȯ�����ּ���!');
			if(document.form1.option2_name.value.length==0) {
				document.form1.option2_name.focus();
			} else {
				document.form1.optname2[0].focus();
			}
			return;
		}

		//�ɼ�2�� �Է��ߴ��� �˻�
		if(document.form1.option1.value.length==0 && document.form1.option2.value.length>0) {
			alert('�ɼ�2�� �ɼ�1 �Է��� �Է°����մϴ�.');
			document.form1.option1_name.focus();
			return;
		}

		//�ɼ�1�� ���� ���� �˻�
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
			alert('�ɼǺ� ������ ��� �Է��ϰų� ��� �Է����� �ʾƾ� �մϴ�.');
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
				alert("�ɼ� ������ ���ڸ� �Է��� �ϼž� �մϴ�.");
				document.form1.option_price.focus();
				return;
			}
			temp2=temp2.substring(temp+1);
		}

		//������ �� ���ڰ˻�
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
						alert("�Է��Ͻ� ������ �ɼ������� ������ �Ѿ����ϴ�. ("+(i+1)+" °�� "+(j+1)+" °ĭ)");
						document.form1["optnumvalue["+j+"]["+i+"]"]. focus();
						return;
					}
				}
			}
		}
		if(isquan==true) {
			if(!confirm("���� �Է��� �ȵ� �ɼ������� ������ �������� ��ϵ˴ϴ�.\n\n��� �Ͻðڽ��ϱ�?")) {
				quanobj.focus();
				return;
			}
		}

	} else if(shop=="layer2"){
		if (document.form1.toption_price.value.length!=0 && document.form1.toption1.value.length==0) {
			alert("Ư���ڵ庰������ �Է��ϸ� �ݵ�� Ư���ڵ��Է�1���� ������ �Է��ؾ� �մϴ�.");
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
				alert("�ɼ� ������ ���ڸ� �Է��� �ϼž� �մϴ�.");
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
		alert('�ø��÷��� �ϴ� ���Ͽ뷮�� 300K�̻��Դϴ�.\n���Ͽ뷮�� üũ�Ͻ��Ŀ� �ٽ� �̹����� �÷��ּ���');
		return;
	}
	tempcontent = document.form1.content.value;
<?if ($predit_type=="Y" && false){ ?>
	if(mode=="update" && tempcontent.length>0 && tempcontent.indexOf("<")==-1 && tempcontent.indexOf(">")==-1 && !confirm("�������� ����߰��� �ؽ�Ʈ�θ� �Է��Ͻ� �󼼼�����\n�ٹٲٱⰡ �����Ǿ� ���θ����� �ٸ��� ������ �� �ֽ��ϴ�.\n\n���Է��Ͻðų� ���� ���θ����� �ش� ��ǰ�� �󼼼�����\n�״�� ���콺�� �巡���Ͽ� �ٿ��ֱ⸦ �ؼ� ���Է��ϼž� �մϴ�.\n\n���� ���� �������� �ʰ� �����Ͻ÷��� [Ȯ��]�� ��������.")){
		return;
	}
<?}?>
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}

	if(confirm("��ǰ ������ �����Ͻðڽ��ϱ�?")) {
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
		alert('������ �ٹ̱�� �ѻ�ǰ�� 3������ ��� �����մϴ�.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked==true) alert('��ǰ �߰�/���� �̹����� ū�̹������� �ڵ� �����˴ϴ�.\n\n������ �߰�/���� �̹����� �����˴ϴ�.');
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
	if (reserveType=="Y") { max=5; addtext="/Ư������(�Ҽ���)";} else { max=6; }
	if (thisForm.reserve.value.bytes() > max) {
		alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "����"+addtext+" " + max + "�� �̳��� �Է��� �����մϴ�.");
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

	<? /* ������ ���� �߰� jdy */?>
		if (document.form1.up_rq_cost) {
			if (document.form1.up_rq_cost.value.length==0) {
				alert("��ǰ���ް��� �Է����ּ���.");
				document.form1.up_rq_cost.focus();
				return;
			}

			if(isDigitSpecial(document.form1.up_rq_cost.value,"")) {
				alert("��ǰ���ް��� ���ڷθ� �Է��ϼ���.");
				document.form1.up_rq_cost.focus();
				return;
			}
		}


		if (document.form1.up_rq_com) {
			if (document.form1.up_rq_com.value.length==0) {
				alert("�����Ḧ �Է����ּ���.");
				document.form1.up_rq_com.focus();
				return;
			}

			if(isDigitSpecial(document.form1.up_rq_com.value,"")) {
				alert("������� ���ڷθ� �Է��ϼ���.");
				document.form1.up_rq_com.focus();
				return;
			}
		}

	<? /* ������ ���� �߰� jdy */?>

/*
	if(form.rq_name.value.length==0) {
		alert("��û�ڸ� �Է����ּ���.");
		form.rq_name.focus();
		return;
	}
*/

	if(confirm("������ ������ ��û�Ͻðڽ��ϱ�??")) {
		form.mode.value="com";
		form.target="processFrame";
		form.submit();
	}
}


// �ǸŰ� �ڵ����
function sellpriceAutoCalc ( v ) {

	if( document.form1.autoCalc.checked == true ) {

		var sell = document.form1.sellprice;
		var org = document.form1.consumerprice;
		var disc = document.form1.discountRate;

		var sellv = sell.value;
		var orgv = org.value;
		var discv = disc.value;

		discI = parseInt( 100 -( ( sellv / orgv ) * 100 ) );

		// �ǸŰ� �Է½�
		if( v == 'sell' && orgv > 0 ) disc.value = discI;

		// ���� �Է½�
		if( v == 'org' && orgv > 0 ) disc.value = discI;

		// ������ �Է½�
		if( v == 'disc' ) {
			if( discv < 0 || discv > 100 ) {
				alert('�������� 0-100 ���� �Է°����մϴ�.');
				disc.value = 0;
			} else {
				if( orgv > 0 ) sell.value = parseInt( ( ( orgv - ( ( orgv / 100 ) * discv ) ) / 100 ) * 100 );
			}
		}

	}
}



//-->
</SCRIPT>

<?
$productname = $_data->productname;
if(strlen($_data->option_quantity)>0) $searchtype=1;

// Ư���ɼǰ��� üũ�Ѵ�.
$dicker = $dicker_text="";
if (strlen($_data->etctype)>0) {
	$etctemp = explode("",$_data->etctype);
	$miniq = 1;          // �ּ��ֹ����� �⺻�� �ִ´�.
	$maxq = "";
	for ($i=0;$i<count($etctemp);$i++) {
		if ($etctemp[$i]=="BANKONLY")                    $bankonly="Y";        // ��������
		else if (substr($etctemp[$i],0,11)=="DELIINFONO=")     $deliinfono=substr($etctemp[$i],11);  // ���/��ȯ/ȯ������ ������� ����
		else if ($etctemp[$i]=="SETQUOTA")               $setquota="Y";        // �����ڻ�ǰ
		else if (substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
		else if (substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);  // �ִ��ֹ�����
		else if (substr($etctemp[$i],0,5)=="ICON=")      $iconvalue=substr($etctemp[$i],5);  // �ִ��ֹ�����
		else if (substr($etctemp[$i],0,9)=="FREEDELI=")  $freedeli=substr($etctemp[$i],9);  // �����ۻ�ǰ
		else if (substr($etctemp[$i],0,7)=="DICKER=") {  $dicker='Y'; $dicker_text=str_replace("DICKER=","",$etctemp[$i]); }  // ���ݴ�ü����
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

include_once "./PrdRegist.js.php";
?>
<!-- �����Ϳ� ���� ȣ�� -->
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
<!-- # �����Ϳ� ���� ȣ�� -->

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

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15">

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">

				<form name=form1 method=post enctype="multipart/form-data">
				<input type=hidden name=mode>
				<input type=hidden name=prcode value="<?=$prcode?>">
				<input type=hidden name=htmlmode value='wysiwyg'>
				<input type=hidden name=delprdtimg>
				<input type=hidden name=option1>
				<input type=hidden name=option2>
				<input type=hidden name=option_price>

				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>ī�װ� ����</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>

					<table width=100% border=0 cellspacing=0 cellpadding=0>
						<tr height="22" align="center">
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">��з�</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">�ߺз�</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">�Һз�</div></th>
							<td align="center"><img src="images/icon_arrow02.gif" border="0"></td>
							<th width="25%" style="border:1px solid #E7E7E7; background:#FEFCE2; height:23px;"><div style="width:150px;">���з�</div></th>
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
							if($ctype=="X") echo " (���Ϻз�)";
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
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>��ǰ����</B><a href="javascript:document.location.reload()">[���ΰ�ħ]</a></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<!-- �뿩 ��ǰ ( ��ǰ ���� ) -->
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
					<col width=250>
					<col width=95>
					<col>
					</colgroup>
					<? if(substr($_venderdata->grant_product,3,1)=="N") {?>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ����</td>
						<td colspan="3" style="padding:7px 7px">
						<input type=radio id="idx_display1" name=display value="Y" <? if ($_data->display=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display1>���̱� [ON]</label>
						<img width=50 height=0>
						<input type=radio id="idx_display2" name=display value="N" <? if ($_data->display=="N") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display2>�Ⱥ��̱� [OFF]</label>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<? } else {?>

					<input type=hidden name=display value="<?=$_data->display?>">

					<? }?>					
					<!-- �뿩 ��ǰ ( ��ǰ ���� ) -->					
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ ����</td>
						<td colspan=3  style="padding:7px 7px"><? echo ($_data->rental == '2')?'�뿩��ǰ':'�ǸŻ�ǰ';?><input type="hidden" name="rental" value="<?=$_data->rental?>" /></td>						
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><font color=FF4800>*</font> ��ǰ��</td>
						<td colspan="3" style="padding:7px 7px"><input name=productname value="<?=ereg_replace("\"","&quot",$_data->productname)?>" maxlength=250 style="width:388" onKeyDown="chkFieldMaxLen(250)"></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>


					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰȫ������</td>
						<td colspan="3" style="padding:7px 7px"><input name="prmsg" value="<?=ereg_replace("\"","&quot",$_data->prmsg)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" style="width:100%"></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>


					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">���/������</td>
						<td colspan="3" style="padding:7px 7px">
<?
						echo " ".str_replace("-","/",substr($_data->modifydate,0,16))."</font>\n";
						echo "(��ǰ�ڵ� : <font color=#003399>".$_data->productcode."</font>)";
						echo "&nbsp;&nbsp;&nbsp;<a href=\"http://".$_venderdata->shopurl."?productcode=".$_data->productcode."\" target=_blank><img src=\"images/icon_goprdetail.gif\" align=absmiddle border=0></font></a>";
?>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ �������</td>
						<td colspan="3" style="padding:7px 7px">
						<input type=checkbox id="idx_insertdate0" name=insertdate value="Y" <?=($insertdate_cook=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate0>��� ���� ����</label>&nbsp;&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">��üũ�Ͻø� ��ǰ������, �ֱ� ��ǰ���� ����˴ϴ�.</FONT>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>

					<? if($_data->rental == '2'){ ?>
					<!-- �뿩 ��ǰ ( ��ǰ ���� ) -->					
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ���� ��<br /> ���� ����</td>
						<td colspan="3" style="padding:7px 7px">
							<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
								<tr>
									<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>����</th>
									<td style="text-align:left; padding:0px 10px;">
									<? if($rentProduct['istrust']=='0'){ ?>
										<input type="hidden" name="istrust" value="0" />��Ź���� (������  <?=number_format($commi['main'])?>%)
									<? }else if($rentProduct['istrust']=='-1'){ ?>
										<input type="hidden" name="istrust" value="-1" />��Ź���� ���(������  <?=number_format($commi['main'])?>%)	
									<? }else{ ?>
										<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />�������� (������  <?=number_format($commi['self'])?>%)&nbsp;<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> onclick="javascript:toggleTrust()" />��Ź���� ��û (������  <?=number_format($commi['main'])?>%)										
									<? } ?>
									</td>									
									<th style="width:100px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��ǰ Ÿ��</th>
									<td style="text-align:left; width:120px; padding-left:5px;">
										<? if($rentProduct['istrust'] ==  '1'){ ?>
										<input type=radio id="itemType1" name="itemType" value="product" <? if($rentProduct['itemType'] != 'location') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>��ǰ</label> &nbsp;
										<input type=radio id="itemType2" name="itemType" value="location" <? if($rentProduct['itemType'] == 'location') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>���</label> &nbsp;
										<? }else{ echo ($rentProduct['itemType'] != 'location')?'��ǰ':'���';
										} ?>
									</td>
									<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
									<th style="width:100px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>���ݱ���</th>
									<td style="text-align:left;padding:0px 10px;">
										<? switch($categoryRentInfo['pricetype']){
												case 'time': echo '�ð����� ���'; break;
												case 'day': echo '�Ϸ�(24�ð�)���� ���'; break;
												case 'checkout': echo '������(����2��~����11��) ���'; break;
												default: echo '����'; break;
										} ?>
									</td>
									<? } ?>									
									<th style="width:100px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��������</th>
									<td style="text-align:left;padding:0px 10px; width:120px;">
										<? echo ($categoryRentInfo['useseason'] == '1')?'������ ���':'������';?>
									</td>																
								</tr>
							</table>
						</td>
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������</td>
						<td colspan="3" style="padding:7px 7px">
						<?
							if($rentProduct['istrust'] ==  '1'){ 
								// �뿩 ����� ���� ����Ʈ
								$value = array("vender"=>$_VenderInfo->getVidx()); // ���� �� ǥ��
								$localList = rentLocalList( $value );
								if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;							
								?>								
								<select name="location" style="float:left">
									<option value="0">--������ ����--</option>
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
								<input type="button" value="����� ����" onclick="javascript:openLocalWin();" style="margin-left:5px;" />
						
								
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
								��Ź ���� �� ���翡�� ��ǰ ���� â�� �����帳�ϴ�.
							<? } ?>
						</TD>
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					
					<TR>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��Ż�ɼǻ�ǰ</td>
						<td colspan="3" style="padding:7px 7px">						
								<?	if($rentProduct['istrust'] ==  '1'){ ?>
								<input type="button" value="�뿩��ǰ�ɼ�" onclick="rentProdOptManager(<?=$_data->pridx?>);">
								<?	} ?>
								<input type="button" value="�뿩����" onclick="bookingSchedulePop(<?=$_data->pridx?>);">
								<input type="button" value="�����԰�" onclick="bookingRepair(<?=$_data->pridx?>);">
								<?
								if ($rentProduct['istrust'] ==  '1' && retnOptionUseCnt($_data->pridx) == 0 ) {
									echo "<br><span style=\"color:#ec2f36; margin-top:5px; display:block;\"><strong>�ɼ��� �ּ� 1�� �̻� �Է��� �ּ���!</strong></span>";
								}
								?>
								
						</TD>
					</TR>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>				
				<?	}	?>


					<? /* �߰� jdy */?>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ���� �� ��������</td>
						<td colspan="3" style="padding:7px 7px">
						<? if ($coupon_use=="1") { ?>
						<input type=checkbox id="idx_etcapply_coupon" name=etcapply_coupon value="Y" <?=($_data->etcapply_coupon=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_coupon>�������� ����Ұ�</label>
						&nbsp;&nbsp;&nbsp;
						<? }else{ ?>
						<input type="hidden" name=etcapply_coupon value="<?= $_data->etcapply_coupon ?>" />
						<? } ?>

						<? if ($reserve_use=="1") { ?>
						<input type=checkbox id="idx_etcapply_reserve" name=etcapply_reserve value="Y" <?=($_data->etcapply_reserve=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_reserve>����������Ұ�</label>
						&nbsp;&nbsp;&nbsp;
						<? }else{ ?>
						<input type="hidden" name=etcapply_reserve value="<?= $_data->etcapply_reserve ?>" />
						<? } ?>
						<input type=checkbox id="idx_etcapply_gift" name=etcapply_gift value="Y" <?=($_data->etcapply_gift=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_gift>���Ż���ǰ����Ұ�</label>
						<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($_data->etcapply_return=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>��ȯ��ȯ�� �Ұ�</label>
						<input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>���ݰ����� ����ϱ�</label> <font style="color:#2A97A7;font-size:8pt">(���� ��ǰ�� �Բ� ���Ž� ������ ���ݰ����θ� ����˴ϴ�.)</FONT>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<? /* �߰� jdy */?>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><font color=FF4800>*</font> <?=($gongtype=="Y"?"������":"�ǸŰ���")?></td>
						<td colspan="3" style="padding:7px 7px">

								�ǸŰ� : <input name=sellprice value="<?=(int)(strlen($_data->sellprice)>0?$_data->sellprice:"0")?>" size=16 maxlength=10 class="input" <?=($_data->assembleuse=="Y"?"disabled style='background:#C0C0C0'":"")?> style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('sell');" onfocus="sellpriceAutoCalc('sell');">��
								=
								���� : <input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('org');" onfocus="sellpriceAutoCalc('org');" >��
								-
								������ : <input name=discountRate value="<?=(int)(strlen($_data->discountRate)>0?$_data->discountRate:"0")?>" size=3 maxlength=3 class="input" style="text-align:center; font-weight:bold; width:40px;" onkeyup="sellpriceAutoCalc('disc');">%
								(<input type="checkbox" id="autoCalc"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="autoCalc">�ڵ����</label>)

								<br><span class="font_orange">* ���� <strike>5,000</strike>�� ǥ���, 0 �Է½� ǥ��ȵ�.&nbsp;</span>

						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<? /* ****** �߰� jdy *********?>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ���Ű���</td>
						<td colspan="3" style="padding:7px 7px">
						<input name="productdisprice" value="<?=ereg_replace("\"","&quot",$_data->productdisprice)?>" size=20 maxlength=50 onKeyDown="chkFieldMaxLen(50)" style="width:20%">
						<br><br><input type=checkbox id="idx_dicker1" name=dicker value="Y" <? if ($_data) { if ($dicker=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dicker1><b>�ǸŰ��� ��ü����</b></label> &nbsp;<input type=text name=dicker_text value="<?=$dicker_text?>" size=20 maxlength=20 onKeyDown="chkFieldMaxLen(20)" > <font style="color:#2A97A7;font-size:8pt">* ��) �ǸŴ���ǰ, ��㹮��(000-000-000)</font><br /><!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b>�� ��ǰ �ǸŰ��� ��� ���ϴ� ������ ��½�Ű�� ����Դϴ�.<br> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> �Է°��� ���� ���� �ѱ� 10��, ���� 20�ڷ� ���ѵǾ� �ֽ��ϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> ���� �ֹ��� ������� �ʽ��ϴ�.
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<? /******* �߰� jdy ******** */?>
					<? /****** ������ ���� ���� jdy ************/?>
					<?

					if ($account_rule=="1" || $commission_type=="1") {
					//���ް��� ��ǰų�.. ���������� ���� ��������??�� ��Ÿ��.

						$adjust_title = "���� ������";
						if($account_rule) $adjust_title = "���� ���ް�";

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

									$com_status = "<b>���� ���ް� <span class=\"font_blue\">".$cf_num."</span>�� (������ ".$adjust."��) </b>";
								}else{
									$com_status = "<b>���δ��</b>";
								}

								if ($_data->status == '') {
									$com_status = "";
								}else if ($_data->status == '1') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>���ް� ��û�� <span class=\"font_blue\">".$rq_num."</span>��</b>";
								}else if ($_data->status == '3') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>���ް� ��û�ź� <span class=\"font_blue\">".$rq_num."</span>��</b>";
								}

								$com_title = "���ް�";
								$com_input = "<input type=text name=up_rq_cost value\"\" size=10 maxlength=10 onkeyup=\"strnumkeyup(this)\" class=input>��";
							?>
							<?= $com_status ?>
							<br/>
							<font style="color:#2A97A7;font-size:8pt">* ������ = �ǸŰ� - ���λ�ǰ���ް�</font>
						<? }else{

								$cf_num = $_data->cf_com;
								$rq_num = $_data->rq_com;
								if ($cf_num=="") $cf_num="0";

								if ($_data->first_approval=="1") {
									$com_status = "<b>���� ������ <span class=\"font_blue\">".$cf_num."</span>%</b>";
								}else{
									$com_status = "<b>���δ��</b>";
								}

								if ($_data->status == '') {
									$com_status = "";
								}else if ($_data->status == '1') {
									$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>������ ��û�� <span class=\"font_blue\">".$rq_num."</span>%</b>";
								}else if ($_data->status == '3') {
									$com_status = "&nbsp;&nbsp;&nbsp;&nbsp;<b>������ ��û�ź� <span class=\"font_blue\">".$rq_num."</span>%</b>";
								}
								$com_title = "������";
								$com_input = "<input type=text name=up_rq_com value=\"\" size=3 maxlength=3 onkeyup=\"strnumkeyup(this)\" class=input>%";
							?>
							<?= $com_status ?>
						<? } ?>
						&nbsp;&nbsp;&nbsp;&nbsp;<button style="color:#ffffff;background-color:#000000;border:0;width:80px;height:25px;cursor:pointer" onclick="commissionDivView();">�����û</button>
						<? if (!$_data->status) { ?>
						&nbsp;&nbsp;<span style="color:red;font-weight:bold">* <?= $adjust_title ?>�� �������� �ʾҽ��ϴ�. <?= $adjust_title ?>�� ��û���ּ���.</span>
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
										<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"><B>��û�� �̸�</td>
										<td style=padding:7,10>
											<input type=text name=rq_name value="" size=10 class=input>
										</td>
									</tr>
									<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
									<tr><td></td>
										<td style="padding-top:10px;text-align:right;"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionRequest()">��û</span></td>
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

					<? /************ �����ݻ�� ���� jdy ************/?>
					<? if ($reserve_use=="1") { ?>

					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������(��)</td>
						<td style="padding:7px 7px" colspan="3"><input name=reserve value="<?=$_data->reserve?>" size=18 maxlength=6 onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);"><select name="reservetype" style="width:77;font-size:8pt;margin-left:1px;" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N"<?=($_data->reservetype!="Y"?" selected":"")?>>������(��)</option><option value="Y"<?=($_data->reservetype!="Y"?"":" selected")?>>������(%)</option></select><br><font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* �������� �Ҽ��� ��°�ڸ����� �Է� �����մϴ�.<br>* �������� ���� ���� �ݾ� �Ҽ��� �ڸ��� �ݿø�.</span></td>
					<? }else{ ?>
						<input type="hidden" name=reserve value=""/>
					<? } ?>
					<? /************ �����ݻ�� ���� jdy ************/?>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ���Կ���</td>
						<td style="padding:7px 7px" colspan="3"><input name=buyprice value="<?=$_data->buyprice?>" size=18 maxlength=10></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������</td>
						<td style="padding:7px 7px"><input name=production value="<?=$_data->production?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������</td>
						<td style="padding:7px 7px"><input name=madein value="<?=$_data->madein?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(30)">&nbsp;<a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �귣��</td>
						<td style="padding:7px 7px"><input name=brandname value="<?=$_data->brandname?>" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" align="absmiddle"></a><br>
						<font style="color:#2A97A7;font-size:8pt">�� �귣�带 ���� �Է½ÿ��� ��ϵ˴ϴ�.</font></td></td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �𵨸�</td>
						<td style="padding:7px 7px"><input name=model value="<?=$_data->model?>" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �����ڵ�</td>
						<td style="padding:7px 7px" colspan="3"><input name=selfcode value="<?=$_data->selfcode?>" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(20)"><br><font style="color:#2A97A7;font-size:8pt">* ���θ����� �ڵ����� �߱޵Ǵ� ��ǰ�ڵ�ʹ� ������ ��� �ʿ��� ��ü��ǰ�ڵ带 �Է��� �ּ���.</font></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �����</td>
						<td style="padding:7px 7px" colspan="3"><input name=opendate value="<?=$_data->opendate?>" size=18 maxlength=8>&nbsp;&nbsp;��) <?=DATE("Ymd")?>(��ó����)<br>
						<font style="color:#2A97A7;font-size:8pt">* ���ݺ� ������ �� ���޾�ü ���� ����� ���˴ϴ�.<br>* �߸��� ����� �������� ���� ������ �������� å�����ž� �˴ϴ�.</font></td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ����</td>
						<td colspan="3" style="padding:7px 7px">
<?
						$quantity=$_data->quantity;
						if($_data->quantity==NULL) $checkquantity="F";
						else if($_data->quantity<=0) $checkquantity="E";
						else $checkquantity="C";
						if($quantity<0) $quantity="";

						$arrayname= array("ǰ��","������","����");
						$arrayprice=array("E","F","C");
						$arraydisable=array("true","true","false");
						$arraybg=array("silver","silver","white");
						$arrayquantity=array("","","$quantity");
						$cnt = count($arrayprice);
						for($i=0;$i<$cnt;$i++){
							echo "<input type=radio id=\"idx_checkquantity".$i."\" name=checkquantity value=\"".$arrayprice[$i]."\" ";
							if($checkquantity==$arrayprice[$i]) echo "checked "; echo "onClick=\"document.form1.quantity.disabled=".$arraydisable[$i].";document.form1.quantity.style.background='".$arraybg[$i]."';document.form1.quantity.value='".$arrayquantity[$i]."';\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_checkquantity".$i.">".$arrayname[$i]."</label>&nbsp;&nbsp;";
						}
						echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\">��";

						if($checkquantity=="C"){
							echo "<script>document.form1.quantity.disabled=false;document.form1.quantity.style.background='white';</script>\n";
						}else{
							echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
						}
?>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �ּ��ֹ��ѵ�</td>
						<td style="padding:7px 7px"><input type=text name=miniq value="<?=($miniq>0?$miniq:"1")?>" size=5 maxlength=5> �� �̻�</td>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �ִ��ֹ��ѵ�</td>
						<td style="padding:7px 7px">
						<input type=radio id="idx_checkmaxq1" name=checkmaxq value="A" <? if (strlen($maxq)==0 || $maxq=="?") echo "checked ";?> onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq1>������</label>&nbsp;<input type=radio id="idx_checkmaxq2" name=checkmaxq value="B" <? if ($maxq!="?" && $maxq>0) echo "checked"; ?> onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq2>����</label>:<input name=maxq size=5 maxlength=5 value="<?=$maxq?>">�� ����
						<script>
						if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
						else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
						</script>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������ۺ�</td>
						<td colspan="3" style="padding:7px 7px">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td><input type=radio id="idx_deliprtype0" name=deli value="H" <?if($_data->deli_price<=0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype0>�⺻ ��ۺ� <b>����</b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_deliprtype2" name=deli value="F" <?if($_data->deli_price<=0 && $_data->deli=="F") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype2>���� ��ۺ� <b><font color="#0000FF">����</font></b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_deliprtype1" name=deli value="G" <?if($_data->deli_price<=0 && $_data->deli=="G") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype1>���� ��ۺ� <b><font color="#38A422">����</font></b></label>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td><input type=radio id="idx_deliprtype3" name=deli value="N" <?if($_data->deli_price>0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype3>���� ��ۺ� <b><font color="#FF0000">����</font></b> <input type=text name=deli_price_value1 value="<?if($_data->deli_price>0 && $_data->deli=="N") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="Y") echo "disabled style='background:silver'";?> class="input">��</label>
								<br>
								<input type=radio id="idx_deliprtype4" name=deli value="Y" <?if($_data->deli_price>0 && $_data->deli=="Y") echo "checked";?> onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype4>���� ��ۺ� <b><font color="#FF0000">����</font></b> <input type=text name=deli_price_value2 value="<?if($_data->deli_price>0 && $_data->deli=="Y") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="N") echo "disabled style='background:silver'";?> class="input">�� (���ż� ��� ���� ��ۺ� ���� : <FONT COLOR="#FF0000"><B>��ǰ���ż������� ��ۺ�</B></font>)</label>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ������</td>
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
							<td><input type=radio id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($_data->group_check!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">��ǰ������ ������</label>&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">* ��ǰ������ �������� ��� ��� ��ȸ��, ȸ������ ����˴ϴ�.</font><br><input type=radio id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($_data->group_check=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">��ǰ������ ����</label></td>
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
									echo "<td style=\"padding:3px;\">* ȸ������� �������� �ʽ��ϴ�.</td>\n";
								}
?>
								</tr>
								</table>
								</td>
							</tr>
<?
							if($grpcnt!=0) {
								echo "<tr><td align=\"right\"><input type=checkbox id=\"group_codeall_idx\" onclick=\"GroupCodeAll(this.checked,$grpcnt);\"> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"group_codeall_idx\">�ϰ�����/����</label></td></tr>\n";
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
															<td style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"> ��ǰ�������</td>
															<td colspan="3" style="padding:7,7">
																<table border="0" cellpadding="0" cellspacing="0" width="100%">
																	<tr>
																		<td class="td_con1">
																			��ǰ���� ���� :
																			<select name="gosiTemplet" class="select">
																				<option value="">���ø� ����Ʈ �ε���</option>
																			</select>
																		</td>
																	</tr>
																	<tr>
																		<td class="td_con1">
																			<span class="font_orange">
																			�� �׸�� �Ǵ� ���� �� �� �κ��̶� ������ ������� �ش� �׸��� ��ϵ��� �ʽ��ϴ�.<br>
																			�� ��ǰ ���м����� ���� ������� ������ �⺻ ������ �� �κк� �������� �ʿ�� ������ �����մϴ�.<br>
																			�� ������� ���� ����� ���� ��� ������ �ʱ�ȭ�Ǹ�, ��ǰ ���� ����� ����˴ϴ�.
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
																			.dtitleInput{ width:96%; border:1px solid #ccc; font-family:����; letter-spacing:-1px; }
																			.ditemTextarea{ width:98%; line-height:18px;}
																			</style>

																			<script language="javascript" type="text/javascript">
																				function addGosiItem(el,itm){
																					var str = '<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>';
																						str += '<tr>';
																						str +=      '<td class="dtitleTd"><input type="hidden" name="didx[]" value="" /><input type="text" name="dtitle[]" value="'+((itm && itm.title)?itm.title:'')+'" class="dtitleInput" /></td>';
																						str +=      '<td width="60%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"></textarea></td>';
																					if(itm && itm.desc){
																						str +=      '<td width="90" class="dbtnTd" rowspan="2"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
																						str += '<tr><td colspan="2" class="daccTd"><span class="font_orange">* '+itm.desc+'</span></td></tr>';
																					}else{
																						str +=      '<td class="dbtnTd"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
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
																								$j('select[name=gosiTemplet]').append('<option value="">== ��ǰ ���� ���� ==</option>');
																								$j.each(data.items,function(idx,itm){
																									$j('select[name=gosiTemplet]').append('<option value="'+itm.idx+'">'+itm.title+'</option>');
																								});
																								$j('select[name=gosiTemplet]').append('<option value="-1">���� �Է�</option>');
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
																					<td width="90" class="dbtnTd"><img src="/images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="/images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td>
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
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ����� ���� ����</TD>
						<td colspan="3" style="padding:7px 7px">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<colgroup>
							<col width="180">
							<col width="">
							</colgroup>
						<tr>
							<td colspan="2"><input type=radio id="idx_userspec1" name=userspec onclick="userspec_change('N');" value="N" <?if($userspec!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec1>����� ���� ���� ������</label>&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=radio id="idx_userspec0" name=userspec onclick="userspec_change('Y');" value="Y" <?if($userspec=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_userspec0>����� ���� ���� �����</label></td>
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
								<td align="center" height="30"><b>��<img width="20" height="0">��<img width="20" height="0">��</b></td>
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
								<td align="center" height="30"><b>��<img width="20" height="0">��<img width="20" height="0">��<img width="20" height="0">��</b></td>
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
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �˻���</td>
						<td colspan="3" style="padding:7px 7px">
						<input name=keyword value="<? if ($_data) echo $_data->keyword; ?>" size=80 maxlength=100 onKeyDown="chkFieldMaxLen(100)">
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ��ǰ Ư�̻���</td>
						<td colspan="3" style="padding:7px 7px">
						<input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=43 maxlength=200 onKeyDown="chkFieldMaxLen(200)">&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">(��: ����� �뷮ǥ��, TV�� 17��ġ��)</font>
						</td>
					</tr>
					<tr><td height="1" colspan="4" bgcolor="E7E7E7"></td></tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>��������</B></td>
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
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ū�̹���</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile" class=button style="width=300px" onchange="document.getElementById('size_checker').src=this.value;"> <font style="color:#2A97A7;font-size:8pt">(�����̹��� : 550X550)</font>
<?
						if (strlen($_data->maximage)>0 && file_exists($imagepath.$_data->maximage)==true) {
							echo "<br><img src='".$imagepath.$_data->maximage."' height=100 width=200 border=1 alt='URL : /".RootPath.DataDir."shopimages/product/".$_data->maximage."'>";
							echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('1')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
						} else {
							echo "<br><img src=images/space01.gif>";
						}
?>
						<input type=hidden name="vimage" value="<?=$_data->maximage?>">
						<br>
						<input type=checkbox id="idx_imgcheck1" name=imgcheck value="Y" <?if (strlen($_data->minimage)>0 || strlen($row->tinyimage)>0) echo "onclick=PrdtAutoImgMsg()"?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_imgcheck1><font color=#003399>ū �̹����� �߰�/���� �̹��� �ڵ����� (�̹��� ���� ������� ����)</font></label>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �߰��̹���</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile2" class=button style="width=300px" onchange="document.getElementById('size_checker2').src = this.value;" > <font style="color:#2A97A7;font-size:8pt">(�����̹��� : 300X300)</font>
<?
						if (strlen($_data->minimage)>0 && file_exists($imagepath.$_data->minimage)==true){
							echo "<br><img src='".$imagepath.$_data->minimage."' height=80 width=150 border=1 alt='URL : /".RootPath.DataDir."shopimages/product/".$row->minimage."'>";
							echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('2')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
						} else {
							echo "<br><img src=images/space01.gif>";
						}
?>
						<input type=hidden name="vimage2" value="<?=$_data->minimage?>">
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �����̹���</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile3" class=button style="width=300px" onchange="document.getElementById('size_checker3').src = this.value;" > <font style="color:#2A97A7;font-size:8pt">(�����̹��� : 130X130)</font>
<?
						if (strlen($_data->tinyimage)>0 && file_exists($imagepath.$_data->tinyimage)==true){
							echo "<br><img src='".$imagepath.$_data->tinyimage."' height=70 width=120 border=1 alt='URL : /".RootPath.DataDir."shopimages/product/".$_data->tinyimage."'>";
							echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('3')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
						} else {
							echo "<br><img src=images/space01.gif>";
						}
?>
						<input type=hidden name="vimage3" value="<?=$_data->tinyimage?>">
						<input type=hidden name=setcolor value="<?=$setcolor?>">
						<BR>
						<table border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td><input type=checkbox name=imgborder value="Y" <?=(($imgborder)=="Y"?"checked":"")?>></td>
							<td style="padding-top:4px"><font color=#003399>�ű� ��Ͻ�,&nbsp;(&nbsp;</td>
							<td width=10 align=center valign=middle><div id="ColorPreview" style="background-color: #<?=$setcolor?>;height: 10px; width: 15px"></div></td>
							<td style="padding-top:4px"><font color=#003399>&nbsp;)&nbsp;�� ��ǰ �׵θ��� ����!&nbsp;&nbsp;�ٸ� ������-></font></td>
							<td><a href="JavaScript:SelectColor()"><img src="images/ed_color_bg.gif" align=absmiddle border=0></a></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ����ϼ� �̹���</td>
						<td style="padding:7px 7px">
						<input type=file name="wideimage" class=button style="width=300px" onchange="document.getElementById('size_checker3').src = this.value;" >
						<input type=hidden name="attechwide" value="<?=$_data->wideimage?>">
						<?if(is_file($savewideimage.$_data->wideimage)){	?>
							<br/>
							<img src="<?=$savewideimage.$_data->wideimage?>?t=<?=time()?>" width="150"/>
							<a href="JavaScript:DeletePrdtImg('4')"><img src="images/icon_del1.gif" align="bottom" border=0></a>
						<?}?>
						<p style="color:#ff6600">
							* ����ϼ� ���� ���÷��� Ÿ�� �� ����ƮŸ�� ���� �̹����� ÷���ϴ� ����Դϴ�.<br/>
							* �ش� �̹����� ÷������ ���� ���¿��� ����ϼ� ���� ���÷��� Ÿ���� ����Ʈ�� ���� �� ��� ��ǰ �̹����� ���� ���� �ʽ��ϴ�.<br/>
							* ����Ʈ�̹��� ������ ���Ͻ� ��� �� ÷�θ� �Ͻ� �� ���� �Ͻø� ���� �˴ϴ�.<br/>
						</p>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>��ǰ ������</B>

					<? if($predit_type=="Y" && false){?>
					&nbsp;&nbsp;
					<input type=radio id="idx_checkedit1" name=checkedit checked onclick="JavaScript:htmlsetmode('wysiwyg',this)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkedit1>��������� �Է��ϱ�(����)</label>
					&nbsp;&nbsp;
					<input type=radio id="idx_checkedit2" name=checkedit onclick="JavaScript:htmlsetmode('textedit',this);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkedit2>���� HTML�� �Է��ϱ�</label>
					<? } ?>

					</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr>
						<td>
						<textarea wrap=off style="width:100%; height:300" name="content"  lang="ej-editor1"><?=htmlspecialchars($_data->content)?></textarea>
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
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>�߰�����</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<? if($_data->rental != '2'){ ?>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<colgroup>
					<col width=130>
					<col>
					</colgroup>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> �ɼ�����</td>
						<td style="padding:7px 7px">
						<input type=radio id="idx_searchtype0" name=searchtype onclick="ViewLayer('layer0')" value="0" <?if($searchtype=="0") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype0>�ɼ����� ����</label>
						<img width=30 height=0>
						<input type=radio id="idx_searchtype1" name=searchtype <?if($searchtype=="1") echo "checked";?> onclick="ViewLayer('layer1');alert('�ɼ�1, �ɼ�2�� �ִ� 10���� �� �ɼǺ� ���������� �����ϰ� �˴ϴ�.\n\n������ ������ ���̻��� �ɼǵ��� �����˴ϴ�.');" value="1"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>������ ��ǰ �ɼ�</label>
						<img width=30 height=0>
						<input type=radio id="idx_searchtype2" name=searchtype <?if($searchtype=="2") echo "checked";?> onclick="ViewLayer('layer2')" value="2"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>��ǰ �ɼ� ������ ���</label>
						</font>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td colspan=2>
						<div id=layer0 style="margin-left:0;display:hide; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">

						</div>

						<div id=layer1 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
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
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<colgroup>
						<col width=130>
						<col>
							</colgroup>
						<tr>
							<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">�ɼ����� �Է�</td>
							<td style="padding:7px 7px">
							<font color=#FF6000><b>�ɼ�1 : </b></font>
							<input name=option1_name value="<? if (strlen($_data->option1)>0) echo htmlspecialchars($optionarray1[0]); ?>" size=20 maxlength=20>
							<img width=40 height=0>
							<font color=#128C02><b>�ɼ�2 : </b></font>
							<input name=option2_name value="<? if (strlen($_data->option2)>0) echo htmlspecialchars($optionarray2[0]); ?>" size=20 maxlength=20>
							</td>
						</tr>
						<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						</table>

						<table border=0 cellpadding=0 cellspacing=0 bgcolor=#FFFFFF width=100% style="table-layout:fixed">
							<colgroup>
						<col width=14%>
						<col width=2>
						<col width=14%>
						<col width=2>
						<col>
							</colgroup>
						<tr>
							<td bgcolor=#FFF7F0>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<colgroup>
							<col width=2>
							<col width=2>
							<col>
							<col width=2>
							<col width=2>
								</colgroup>
							<tr bgcolor=#FF7100 height=2>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr height=50>
								<td bgcolor=#FF7100 rowspan=25></td>
								<td rowspan=25></td>
								<td align=center><b>�ɼ�1</b></td>
								<td rowspan=25></td>
								<td bgcolor=#FF7100 rowspan=25></td>
							</tr>
							<tr height=1 bgcolor=#DADADA><td></td></tr>
							<tr height=1><td></td></tr>
<?
							for($i=1;$i<=10;$i++){
								if($i==6) echo "<tr height=5><td></td></tr>";
								echo "<tr height=7><td></td></tr>";
								echo "<tr height=19><td align=center><input type=text name=optname1 value=\"".trim(htmlspecialchars($optionarray1[$i]))."\" size=12></td></tr>";
							}
							echo "<tr height=2><td></td></tr>";
							echo "<tr height=2><td colspan=5 bgcolor=#FF7100></td></tr>";
?>
							</table>
							</td>
							<td></td>
							<td bgcolor=#F2F8FD>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<colgroup>
							<col width=2>
							<col width=2>
							<col>
							<col width=2>
							<col width=2>
								</colgroup>
							<tr bgcolor=#0071C3 height=2>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr height=50>
								<td bgcolor=#0071C3 rowspan=25></td>
								<td rowspan=25></td>
								<td align=center><b>����</b></td>
								<td rowspan=25></td>
								<td bgcolor=#0071C3 rowspan=25></td>
							</tr>
							<tr height=1 bgcolor=#DADADA><td></td></tr>
							<tr height=1><td></td></tr>
<?
							for($i=0;$i<10;$i++){
								if($i==5) echo "<tr height=5><td></td></tr>";
								echo "<tr height=7><td></td></tr>";
								echo "<tr height=19><td align=center><input type=text name=optprice size=12 ";
								echo " value=\"".$option_price[$i]."\" ";
								echo "onkeyup=\"strnumkeyup(this)\"></td></tr>";
							}
							echo "<tr height=2><td></td></tr>";
							echo "<tr height=2><td colspan=5 bgcolor=#0071C3></td></tr>";
?>
							</table>
							</td>
							<td></td>
							<td colspan=2 bgcolor=#FFFFFF valign=top>
							<table border=0 cellpadding=0 cellspacing=0 style="table-layout:fixed">
								<colgroup>
							<col width=2>
							<col width=2>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col width=2>
							<col width=2>
								</colgroup>
							<tr bgcolor=#57B54A height=2>
								<td rowspan=4></td>
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
								<td rowspan=4></td>
							</tr>
							<tr height=27 bgcolor=#F1FFEF><td colspan=12 align=center><b>�ɼ�2</b></td></tr>
							<tr height=19 bgcolor=#F1FFEF>
								<td></td>
<?
								for($i=1;$i<=10;$i++){
									echo "<td align=center width=20%><input type=text name=optname2 value=\"".htmlspecialchars($optionarray2[$i])."\" size=12></td>";
								}
?>
								<td></td>
							</tr>
							<tr height=4 bgcolor=#F1FFEF><td colspan=12></td></tr>
							<tr height=2 bgcolor=#57B54A><td colspan=14></td></tr>
							<tr height=7>
								<td colspan=2 rowspan=23></td>
								<td colspan=10></td>
								<td colspan=2 rowspan=23></td>
							</tr>
<?
							for($i=0;$i<10;$i++){
								if($i!=0 && $i!=10) echo "<tr><td colspan=10 height=7></td></tr>";
								else if($i==10) echo "<tr><td colspan=10 height=6></td></tr>
													<tr><td colspan=10 height=1 bgcolor=#DADADA></td></tr>
													<tr><td colspan=10 height=6></td></tr>";
								echo "<tr height=19>";
								for($j=0;$j<10;$j++){
									echo "<td align=center><input type=text name=optnumvalue[".$j."][".$i."] value=\"".$option_quantity_array[$j*10+$i+1]."\" size=12 maxlength=3 onkeyup=\"strnumkeyup(this)\"></td>\n";
								}
								echo "</tr>";
							}
?>
							</table>
							</td>
						</tr>
						</table>
						</div>

						<div id=layer2 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
<?
						$option1="";
						$optname1="";
						if (strlen($_data->option1)>0) {
							$tok = strtok($_data->option1,",");
							$optname1=$tok;
							$tok = strtok("");
							$option1=$tok;
						}
?>
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<colgroup>
						<col width=130>
						<col>
							</colgroup>
						<tr>
							<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">�ɼ�1</td>
							<td style="padding:7px 7px">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<colgroup>
							<col width=40>
							<col>
								</colgroup>
							<tr>
								<td>
								�Ӽ���
								</td>
								<td style="padding-left:5">
								<input name=toptname1 value="<? if (strlen($_data->option1)>0) echo $optname1; ?>" size=30 maxlength=20>&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">���� or ������ or �뷮��</font>
								</td>
							</tr>
							<tr>
								<td>
								�Ӽ�
								</td>
								<td style="padding-left:5">
								<input name=toption1 value="<? if (strlen($_data->option1)>0) echo htmlspecialchars($option1); ?>" maxlength=230 style="width:100%">
								</td>
							</tr>
							<tr>
								<td colspan=2 width=100% style="padding-left:3">
								<font style="color:#2A97A7;font-size:8pt">
								��) ����,�Ķ�,���
								<br>- �Ӽ��� ������ �Է��ϰ� �Ӽ��� ����,����� �Է��ϸ�
								<br><img width=9 height=0>����ڴ� ����,����� �ϳ��� ������ �� �ֽ��ϴ�.
								<br>- �Ӽ����� ��ĭ���� �޸�(,)�� �����Է�
								</font>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
						<tr>
							<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">�ɼ�1�� ���� ����</td>
							<td style="padding:7px 7px">
							<input name=toption_price value="<? if ($_data) echo $_data->option_price; ?>" maxlength=250 style="width:100%">
							<BR style="line-height:2pt">
							<font style="color:#2A97A7;font-size:8pt">
							��) 1000,2000,3000
							<br>- �ɼ�1 �� �Ӽ��� �ϴ��Ϸ� ��ġ�Ǵ� ����, �ɼǿ� ���� ���ݺ����� �Է�
							<br>- �ɼ�1������ ������ �Է��Ͻø� �ǸŰ����� �����մϴ�.
							</font>
							</td>
						</tr>
						<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
						<tr>
							<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">�ɼ�2</td>
							<td style="padding:7px 7px">
<?
							$option2="";
							$optname2="";
							if (strlen($_data->option2)>0) {
								$tok = strtok($_data->option2,",");
								$optname2=$tok;
								$tok = strtok("");
								$option2=$tok;
							}
?>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<colgroup>
							<col width=40>
							<col>
								</colgroup>
							<tr>
								<td>
								�Ӽ���
								</td>
								<td style="padding-left:5">
								<input name=toptname2 value="<? if (strlen($_data->option2)>0) echo $optname2; ?>" size=30 maxlength=20>
								</td>
							</tr>
							<tr>
								<td>
								�Ӽ�
								</td>
								<td style="padding-left:5">
								<input name=toption2 value="<? if (strlen($_data->option2)>0) echo htmlspecialchars($option2); ?>" maxlength=230 style="width:100%">
								</td>
							</tr>
							<tr>
								<td colspan=2 width=100% style="padding-left:3">
								<font style="color:#2A97A7;font-size:8pt">
								- �ɼ�1�� ������ ������ "<B>�ɼ�1�� ���� ����</B>"���� �����մϴ�.
								</font>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
						</table>
						</div>

						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<? } ?>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<colgroup>
					<col width=130>
					<col>
						</colgroup>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ������ �ٹ̱�</td>
						<td style="padding:7px 7px">

						<table border=0 cellpadding=0 cellspacing=0 width=100%>
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
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9"> ���/��ȯ/ȯ������</td>
						<td style="padding:7px 7px">
						<input type=checkbox id="idx_deliinfono1" name=deliinfono value="Y" <?if ($deliinfono=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliinfono1>���/��ȯ/ȯ������ �������</label> <font style="color:#2A97A7;font-size:8pt">(��ȭ�� �ϴܿ� ���/��ȯ/ȯ�������� ����ȵ�)</font>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>



					</table>
					</td>
				</tr>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit('update')"><img src="images/btn_modify06.gif" border=0></A>
					</td>
				</tr>

				<input type=hidden name=iconnum value='<?=$totaliconnum?>'>
				<input type=hidden name=iconvalue>

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

				</table>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

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
