<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

include_once($Dir."lib/ext/product_func.php");


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


// ���� ���� ����Ʈ
$venderList = venderList("vender,id,com_name");

####################### ������ ���ٱ��� check ###############
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

// ���� ����, ������, ���� ��뿩�� ��ȸ jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];
$reserve_use = $shop_more_info['reserve_use'];
$coupon_use = $shop_more_info['coupon_use'];

$adjust_title = "�Ǹ� ���� ������";
if($account_rule) $adjust_title = "���� ���ް�";


$busySeason=$_POST["busySeason"];
$semiBusySeason=$_POST["semiBusySeason"];
$holidaySeason=$_POST["holidaySeason"];


// ���� ����, ������, ���� ��뿩�� ��ȸ jdy

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
		$list_title = "<B>���� ������ �������� ��ǰ���</B>";
	} else {
		$bgcolor="#F0F0F0";
		$list_title = "<B>�ǸŻ�ǰ ���</B>";
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

#��ǰ�̹��� �µθ� ��Ű ����
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	setcookie("imgborder","Y",0,"/".RootPath.AdminDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && ($mode=="insert" || $mode=="modify")) {
	setcookie("imgborder","",time()-3600,"/".RootPath.AdminDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}

#��ǰ��ϳ�¥ ���� ��Ű ����
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

// ���� ���� ���� jdy
$display=$_POST["display"];
$tax_yn = $_POST["tax_yn"];
// ���� ���� ���� jdy

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

//���� �Ǹ� ��ǰ ����
$reservation = ( $_POST["reservation"] == "Y" AND strlen($_POST["reservationDate"]) > 0 ) ? $_POST["reservationDate"] : '' ;


$group_check=$_POST["group_check"];
//$group_code=$_POST["group_code"];

$group_sel_code=$_POST["group_sel_code"];

if($group_check=="Y" && count($group_code)>0) {
	$group_check="Y";
} else {
	$group_check="N";
	$group_sel_code ="";
}

$gonggu_product = $_POST["gonggu_product"];

$sns_state=$_POST["sns_state"];
$present_state=$_POST["present_state"];
$pester_state=$_POST["pester_state"];
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



################################��ǰ�� ȸ��������######################################
$group_code=(array)$_POST["group_code"];
$discount_rates=(array)$_POST["discount_rates"];
$discount_prices=(array)$_POST["discount_prices"];
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
		echo "<script>alert('�ּұ��ż��� ������ 1�� ���� Ŀ�� �մϴ�.');history.go(-1);</script>";exit;
	}
	if ($checkmaxq=="B" && $maxq>=1)		$etctype .= "MAXQ=".$maxq."";
	else if ($checkmaxq=="B" && $maxq<1){
		echo "<script>alert('�ִ뱸�ż��� ������ 1�� ���� Ŀ�� �մϴ�.');history.go(-1);</script>";exit;
	}

	if ($bankonly=="Y" && $setquota=="Y") {
		echo "<script>alert('������������� �����ڻ����δ��� ���ÿ� üũ�Ͻ� �� �����ϴ�.(���������� ���� Ʋ��)');";
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
	$onload="<script>alert('�ش� ��ǰ�̹����� �����Ͽ����ϴ�.');</script>";
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
			echo "<script>alert('��ǰ�ڵ带 �����ϴµ� �����߽��ϴ�. ����� �ٽ� �õ��ϼ���.');";
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
			} else {
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
			}
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

	####################### Ÿ���� �̹��� ���θ��� ���� #############################
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

	#���̵� �̹��� �߰�
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

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$in_content = str_replace('/data/editor_temp/','/data/editor/',$in_content);
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

		$sql = "INSERT tblproduct SET ";
		$sql.= "productcode		= '".$code.$productcode."', ";
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
		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		$sql.= "package_num		= '".(int)$package_num."', ";
		$sql.= "reservation		= '".$reservation."', ";

		// insert �� ���� ó��
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
		$sql.= "rental = '".$_POST["goodsType"]."' ";


		if($insert = mysql_query($sql,get_db_conn())){

			/* ���� ������ ���� jdy */
			$up_rq_com = $_REQUEST['up_rq_com'];
			$up_rq_cost = $_REQUEST['up_rq_cost'];
			insertCommission($vender, $code.$productcode, $up_rq_com, $up_rq_cost, "","1", $_usersession->id);
			/* ���� ������ ���� jdy */

			// ��ǰ������� ����
			$pridx = mysql_insert_id(get_db_conn());
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



			#�߰� ī�װ� �Է� ����
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
			#�߰� ī�װ� �Է� ��

			if(strlen($brandname)>0) { // �귣�� ���� ó��
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



			################################��ǰ�� ȸ��������######################################
			for($i=0;$i<count($group_code);$i++) {

				$sql = "SELECT COUNT(dsidx) as cnt FROM tblmemberdiscount ";
				$sql.= "WHERE productcode ='".$code.$productcode."' AND group_code='".$group_code[$i]."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);

				if($row->cnt>0){	//��ϵ� �׸��� �ִ� ���� update
					$sql = "UPDATE tblmemberdiscount SET ";
					$sql.= "discountYN		= '".$discountYN."', ";
					$sql.= "discountrates		= '".$discount_rates[$i]."', ";
					$sql.= "discountprices		= ".$discount_prices[$i].", ";
					$sql.= "over_discount		= '".$over_discount."' ";
					$sql.= "WHERE productcode	= '".$code.$productcode."' ";
					$sql.= "AND group_code		= '".$group_code[$i]."' ";

					mysql_query($sql,get_db_conn());
				}else{
					$sql = "INSERT INTO tblmemberdiscount (group_code,productcode,discountYN,discountrates,discountprices,over_discount) VALUES( ";
					$sql.= "'".$group_code[$i]."', ";
					$sql.= "'".$code.$productcode."', ";
					$sql.= "'".$discountYN."', ";
					$sql.= "'".$discount_rates[$i]."', ";
					$sql.= $discount_prices[$i].", ";
					$sql.= "'".$over_discount."') ";
					mysql_query($sql,get_db_conn());
				}
			}




			// �뿩 ��ǰ ����
			$rentProductValue = array();
			$rentProductValue['pridx'] = $pridx;
			$rentProductValue['istrust'] = $_POST["istrust"];
			$rentProductValue['location'] = $_POST["location"];
			$rentProductValue['goodsType'] = $_POST["goodsType"];
			$rentProductValue['itemType'] = $_POST["itemType"];		
			$rentProductResult = rentProductSave( $rentProductValue );


			// �뿩��ǰ ���� (������) ��� ��å ����
			rentProductSeasonPriceSave($pridx, $busySeason, $semiBusySeason, $holidaySeason);

			if($popup=="YES") {
				$onload="<script>alert(\"��ǰ�� ����� �Ϸ�Ǿ����ϴ�.".$message."\");</script>";
			} else {
				$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"��ǰ�� ����� �Ϸ�Ǿ����ϴ�.".$message."\");</script>";
			}

			$log_content = "## ��ǰ�Է� ## - �ڵ� $code$productcode - ��ǰ : $productname �ڵ�/���� : $assembleuse ��Ű���׷� : $package_num ���� : $sellprice ���� : $quantity ��Ÿ : $etctype ������: $reserve $display";
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		} else {
			if($popup=="YES") {
				$onload="<script>alert(\"��ǰ ����� ������ �߻��Ͽ����ϴ�.\");</script>";
			} else {
				$onload="<script>parent.HiddenFrame.alert(\"��ǰ ����� ������ �߻��Ͽ����ϴ�.\");</script>";
			}
		}
		$prcode=$code.$productcode;
	} else if ($mode=="delete") {
		$sql = "SELECT vender,display,brand,pridx,assembleuse,assembleproduct,wideimage FROM tblproduct WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row->content,$edimg)){
			foreach($edimg[1] as $timg){
				@unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
		}

		if(is_file($savewideimage.$row->wideimage) && $mode=="delete"){
			@unlink($savewideimage.$row->wideimage);
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

		$vender=(int)$row->vender;
		$vdisp=$row->display;
		$brand=$row->brand;
		$vpridx=$row->pridx;
		$vassembleuse=$row->assembleuse;
		$vassembleproduct=$row->assembleproduct;


		// �ʼ� ���� �����
		$sql = "delete from tblproduct_detail WHERE pridx = '".$vpridx."'";
		mysql_query($sql,get_db_conn());

		#�±װ��� �����
		$sql = "DELETE FROM tbltagproduct WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#���� �����
		$sql = "DELETE FROM tblproductreview WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#���ø���Ʈ �����
		$sql = "DELETE FROM tblwishlist WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#���û�ǰ �����
		$sql = "DELETE FROM tblcollection WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproducttheme WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproduct WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblproductgroupcode WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		#�߰� ī�װ� ���� �����
		$sql = "DELETE FROM tblcategorycode WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());

		/* �߰� ������ ���̺� ���� ���� jdy */
		$sql = "DELETE FROM product_commission WHERE productcode = '".$prcode."'";
		mysql_query($sql,get_db_conn());
		/* �߰� ������ ���̺� ���� ���� jdy */

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
			//�̴ϼ� �׸��ڵ忡 ��ϵ� ��ǰ ����
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

		$log_content = "## ��ǰ���� ## - ��ǰ�ڵ� $prcode - ��ǰ�� : ".urldecode($productname)." $display";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

		delProductMultiImg("prdelete","",$prcode);

		// ��ǰ�������� ����
		_deleteProductDetails($pridx);

		if ($popup=="YES") {
			$onload="<script>alert(\"��ǰ ������ �Ϸ�Ǿ����ϴ�.\");window.close();opener.location.reload()</script>";
		} else {
			$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"��ǰ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
		}
		$prcode="";
	} else if ($mode=="modify") {
		$sql = "SELECT vender,display,brand,pridx,assembleuse,sellprice,assembleproduct FROM tblproduct WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row->content,$edtimg)){
			if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)) $edimg[1] =array();
			foreach($edtimg[1] as $cimg){
				if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
			}
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

		$vender=(int)$row->vender;
		$vdisp=$row->display;
		$brand=$row->brand;
		$vassembleuse=$row->assembleuse;
		$vpridx=$row->pridx;
		$vsellprice=$row->sellprice;
		$vassembleproduct=$row->assembleproduct;

		if(strlen($buyprice) < 1 ) $buyprice = 0 ;

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$in_content,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$in_content = str_replace('/data/editor_temp/','/data/editor/',$in_content);
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

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

		// update �� ���� ó��
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
				$sql.= "sellprice		= ".$sellprice.", ";
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
				$sql.= "sellprice		= ".$sellprice.", ";
				$sql.= "option_price	= '".$option_price."', ";
				$sql.= "option_quantity	= '".$optcnt."', ";
				$sql.= "option1			= '".$option1."', ";
				$sql.= "option2			= '".$option2."', ";
				$sql.= "package_num		= '".(int)$package_num."', ";
			}
		}

		$sql.= "etctype			= '".$etctype."', ";
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
		$sql.= "rental = '".$_POST["goodsType"]."' ";

		$sql.= "WHERE productcode = '".$prcode."' ";
		//echo $sql; exit;
		if($update = mysql_query($sql,get_db_conn())) {


			// ��ǰ�������� ����
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



			#�߰� ī�װ� �Է� ����
			$arr_cate=$_POST["cate"];
			#==����==
			$sql = "DELETE FROM tblcategorycode WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#==���Է�==
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
			#�߰� ī�װ� �Է� ��

			if(strlen($brandname)>0) { // �귣�� ���� ó��
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
					//	echo $sql.'<br>';
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
		}


		################################��ǰ�� ȸ��������######################################
			for($i=0;$i<count($group_code);$i++) {

				$sql = "SELECT COUNT(dsidx) as cnt FROM tblmemberdiscount ";
				$sql.= "WHERE productcode ='".$prcode."' AND group_code='".$group_code[$i]."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);

				if($row->cnt>0){	//��ϵ� �׸��� �ִ� ���� update
					$sql = "UPDATE tblmemberdiscount SET ";
					$sql.= "discountYN		= '".$discountYN."', ";
					$sql.= "discountrates		= '".$discount_rates[$i]."', ";
					$sql.= "discountprices		= ".$discount_prices[$i].", ";
					$sql.= "over_discount		= '".$over_discount."' ";
					$sql.= "WHERE productcode	= '".$prcode."' ";
					$sql.= "AND group_code		= '".$group_code[$i]."' ";

					mysql_query($sql,get_db_conn());
				}else{
					$sql = "INSERT INTO tblmemberdiscount (group_code,productcode,discountYN,discountrates,discountprices,over_discount) VALUES( ";
					$sql.= "'".$group_code[$i]."', ";
					$sql.= "'".$prcode."', ";
					$sql.= "'".$discountYN."', ";
					$sql.= "'".$discount_rates[$i]."', ";
					$sql.= $discount_prices[$i].", ";
					$sql.= "'".$over_discount."') ";
					mysql_query($sql,get_db_conn());
				}
			}




		// �뿩 ��ǰ ����
		$rentProductValue = array();
		$rentProductValue['pridx'] = $vpridx;
		$rentProductValue['istrust'] = $_POST["istrust"];
		$rentProductValue['location'] = $_POST["location"];
		$rentProductValue['goodsType'] = $_POST["goodsType"];
		$rentProductValue['itemType'] = $_POST["itemType"];
		$rentProductResult = rentProductSave( $rentProductValue );

		// �뿩��ǰ ���� (������) ��� ��å ����
		rentProductSeasonPriceSave($pridx, $busySeason, $semiBusySeason, $holidaySeason);

		if($popup=="YES") {
			$onload="<script>alert(\"��ǰ�� �����Ǿ����ϴ�.$message\");</script>";
		} else {
			$onload="<script>parent.ListFrame.GoPageReload();parent.HiddenFrame.alert(\"��ǰ�� �����Ǿ����ϴ�.$message\");</script>";
		}

		$log_content = "## ��ǰ���� ## - �ڵ� $prcode - ��ǰ : $productname ���� : $sellprice ���� : $quantity ��Ÿ : $etctype ������ : $reserve ��¥���� : ".(($insertdate=="Y")?"Y":"N")." $display";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}
} else {
	$onload="<script>alert(\"��ǰ�̹����� �� �뷮�� ".ceil($file_size/1024)
	."Kbyte�� 500K�� �ѽ��ϴ�.\\n\\n�ѹ��� �ø� �� �ִ� �ִ� �뷮�� 500K�Դϴ�.\\n\\n"
	."�̹����� gif�� �ƴϸ� �̹��� ������ �ٲپ� �ø��ø� �뷮�� �پ��ϴ�.\");history.go(-1);</script>\n";
}

//################# 500K�� �Ѵ� �̹��� üũ
if ((strlen($userfile[name])>0 && $userfile[size]==0) || (strlen($userfile2[name])>0 && $userfile2[size]==0) || (strlen($userfile3[name])>0 && $userfile3[size]==0)) {
	 $onload="<script>alert(\"��ǰ�̹����� �뷮�� 500K�� �Ѵ� �̹����� �ֽ��ϴ�.\\n\\n500K�� �Ѵ� �̹����� ��ϵ��� �ʽ��ϴ�..\\n\\n"
		."�̹����� gif�� �ƴϸ� �̹��� ������ �ٲپ� �ø��ø� �뷮�� �پ��ϴ�.\");</script>\n";
}
//###############################################

//������ ���� jdy
if ($mode =="comm_ok") {

	$commission_result = $_POST['commission_result'];

	if ($prcode) {
		confirmCommission($prcode, $commission_result, $_usersession->id);
	}

	if($popup=="YES") {
		$onload="<script>alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	} else {
		$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	}

}


if ($mode =="comm_admin") {

	$sql = "select vender from tblproduct where productcode='".$prcode."'";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_array($result);


	if ($prcode) {
		/* ���� ������ ���� jdy */
		$up_rq_com = $_REQUEST['up_rq_com'];
		$up_rq_cost = $_REQUEST['up_rq_cost'];
		insertCommission($data[0], $prcode, $up_rq_com, $up_rq_cost, "", "1", $_usersession->id);
		/* ���� ������ ���� jdy */
	}
	if($popup=="YES") {
		$onload="<script>alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	} else {
		$onload="<script>parent.ProductListReload('".$code."');parent.HiddenFrame.alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	}

}
//������ ���� jdy
?>

<? include "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script> var $j = jQuery.noConflict();</script>
<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--
<script language="javascript" type="text/javascript" src="/js/fxTextAreaAutoResizer.js"></script> -->

<script type="text/javascript" src="<?=$Dir?>lib/DropDown.js.php"></script>
<script>var LH = new LH_create();</script>

<script for="window" event="onload">LH.exec();</script>
<script>LH.add("parent_resizeIframe('AddFrame')");</script>

<style type="text/css">
	@import url("/css/common.css");
	#showMemSale {width:240px; margin:0px; padding:10px; position:absolute; background:#ffffff; color:#666; font-size:11px; font-family:����; font-weight:100; border:1 solid #ccc; visible; z-index:100; visibility:hidden;}
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
	if (confirm("�ش� ��ǰ�� �����Ͻðڽ��ϱ�?")) {
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
	alert("���� �غ��� �Դϴ�.");
	//window.open("","iconlist","height=343,width=440,toolbar=no,menubar=no,scrollbars=no,status=no");
	//document.iconlist.submit();
}

function DeletePrdtImg(temp){
	if(confirm('�ش� �̹����� �����Ͻðڽ��ϱ�?')){
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
		alert('�� ��ǰ�� 3������ �������� ����� �� �ֽ��ϴ�.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked==true) alert('��ǰ �߰�/���� �̹����� �� �̹������� �ڵ� �����˴ϴ�.\n\n������ �߰�/���� �̹����� �����˴ϴ�.');
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
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
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
	alert("���� �غ��� �Դϴ�.");
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
	if(obj.checked==true) {	//�̹��� ��ũ ���
		for(var jj=1;jj<=3;jj++) {
			idx=jj;
			if(idx==1) idx="";
			document.form1["userfile"+idx].style.display='none';
			document.form1["userfile"+idx+"_url"].style.display='';
			document.form1["userfile"+idx].disabled=true;
			document.form1["userfile"+idx+"_url"].disabled=false;
		}
	} else {				//÷������ ���
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

/*################################### �±װ��� ���� #####################################*/
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
  			data = "�Ͻ������� �±� ������ �ҷ��� �� �����ϴ�.\n\n�±� ���� ����� ��� �Ŀ� �̿��� �ֽʽÿ�. \n\n��ǰ������  ���������� �����Ͻ�  �� �ֽ��ϴ�.";
  		}
  		tagElem.innerHTML = data;
		tagElem.style.height = "68";
		tagElem.style.overflowY = "auto";
  	}catch(e) {}
}

function delTagName(prcode,tagname) {
<? if(_DEMOSHOP=="OK" && getenv("REMOTE_ADDR")!=_ALLOWIP) { ?>
	alert("������������� ������ �Ұ��� �մϴ�.");
<? } else { ?>
	if(confirm("\""+tagname+"\" �±׸� �����Ͻðڽ��ϱ�?")) {
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

/*################################### �±װ��� ��   #####################################*/


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
	alert("�ѹ� ��ϵ� ��ǰ �ǸŰ��� Ÿ���� ������ �Ұ����ϹǷ� ������ ������ �ּ���.");
}
<? } ?>

/*#################�߰� ī�װ� ����#########################*/
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
		alert('ī�װ��� �������ּ���');
		return;
	}else if(ret=="<?=substr($code,0,12)?>"){
		alert('���� ī�װ��� ������ �� �����ϴ�.');
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
	oTd.innerHTML = "<a href='javascript:void(0)' onClick='cateDel(this.parentNode.parentNode)'><input type='button' value='����' align=absmiddle></a>";
}
/*#################�߰� ī�װ� ��#########################*/

function webftp_popup() {
	window.open("design_webftp.popup.php","webftppopup","height=10,width=10");
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
<!-- �����Ϳ� ���� ȣ�� -->
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
<!-- # �����Ϳ� ���� ȣ�� -->

<table cellpadding="0" cellspacing="0" width="97%" align='right'>
<tr>
	<td style="BORDER:#0F8FCB 2px solid; padding-top:10px; padding-bottom: 10px; padding-left:5px; padding-right:5px;" bgcolor="#FFFFFF">
		<table cellpadding="0" cellspacing="0" width="100%">
		<?if ($popup== "YES") {?>
		<tr>
			<td align="center">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td height="29">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td height="28" class="link" align="right"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : ��ǰ���� &gt;ī�װ�/��ǰ���� &gt; <span class="2depth_select">��ǰ ��� �� ����</span></td>
						</tr>
						<tr>
							<td><img src="images/top_link_line.gif" width="100%" height="1" border="0"></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr><td height="8"></td></tr>
							<tr>
								<td>
								<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
								<TR>
									<TD><IMG SRC="images/product_register_title.gif"  ALT=""></TD>
									</tr><tr>
									<TD width="100%" background="images/title_bg.gif" height=21></TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							<tr><td height="3"></td></tr>
							<tr>
								<td style="padding-bottom:3pt;">
								<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
								<TR>
									<TD><IMG SRC="images/distribute_01.gif"></TD>
									<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
									<TD><IMG SRC="images/distribute_03.gif"></TD>
								</TR>
								<TR>
									<TD background="images/distribute_04.gif"></TD>
									<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
									<TD width="100%" class="notice_blue">�Ǹ��� ��ǰ�� ���,����,���� �մϴ�. ��ǰ�� �⺻����, ��������, �߰��ɼ�����, ��ǰ����, ��ǰ�̹��� ���� ������ �� �ֽ��ϴ�.</TD>
									<TD background="images/distribute_07.gif"></TD>
								</TR>
								<TR>
									<TD><IMG SRC="images/distribute_08.gif"></TD>
									<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
									<TD><IMG SRC="images/distribute_10.gif"></TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							<tr><td height="20"></td></tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<?}?>
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
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
				<tr>
					<td align="center">
			<?
				if ($code != substr($prcode,0,12)) $prcode = "";

				if (strlen($code)==12) {
					if (strlen($prcode)>0) {
						/*
						$sql = "SELECT * FROM tblproduct WHERE productcode = '".$prcode."' ";
						*/

						/****** ������ ���� ���� jdy ************/
						$sql = "SELECT p.*, c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval, p.reservation FROM tblproduct p left join product_commission c on p.productcode=c.productcode WHERE p.productcode = '".$prcode."' ";
						/****** ������ ���� ���� jdy ************/

						$result = mysql_query($sql,get_db_conn());

						if ($_data = mysql_fetch_object($result)) {
							$productname = $_data->productname;

							$syncNaverEp = $_data->syncNaverEp;

							if(strlen($_data->option_quantity)>0) $searchtype=1;
							else if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_data->option1)) $searchtype=3;

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

							// Ư���ɼǰ��� üũ�Ѵ�.
							$dicker = $dicker_text="";
							if (strlen($_data->etctype)>0) {
								$etctemp = explode("",$_data->etctype);
								$miniq = 1;		  // �ּ��ֹ����� �⺻�� �ִ´�.
								$maxq = "";
								for ($i=0;$i<count($etctemp);$i++) {
									if ($etctemp[$i]=="BANKONLY")					$bankonly="Y";		// ��������
									else if (substr($etctemp[$i],0,11)=="DELIINFONO=")	 $deliinfono=substr($etctemp[$i],11);  // ���/��ȯ/ȯ������ ������� ����
									else if ($etctemp[$i]=="SETQUOTA")			   $setquota="Y";		// �����ڻ�ǰ
									else if (substr($etctemp[$i],0,6)=="MINIQ=")	 $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
									else if (substr($etctemp[$i],0,5)=="MAXQ=")	  $maxq=substr($etctemp[$i],5);  // �ִ��ֹ�����
									else if (substr($etctemp[$i],0,5)=="ICON=")	  $iconvalue=substr($etctemp[$i],5);  // �ִ��ֹ�����
									else if (substr($etctemp[$i],0,9)=="FREEDELI=")  $freedeli=substr($etctemp[$i],9);  // �����ۻ�ǰ
									else if (substr($etctemp[$i],0,7)=="DICKER=") {  $dicker=Y; $dicker_text=str_replace("DICKER=","",$etctemp[$i]); }  // ���ݴ�ü����

									/*switch ($etctemp[$i]) {
										case "BANKONLY": $bankonly = "Y";break;
										case "SETQUOTA": $setquota = "Y";break;
									}*/
								}
							}
							if(strlen($iconvalue)>0) {
								for($i=0;$i<strlen($iconvalue);$i=$i+2) {
									$iconvalue2[substr($iconvalue,$i,2)]="Y";
									//echo "<br>>>>>".substr($iconvalue,$i,2);
								}
							}
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
							echo "<script>alert('�ش� ��ǰ�� �������� �ʽ��ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
							exit;
						}
					}

					if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_data->option1)){
						$optcode = substr($_data->option1,5,4);
						$_data->option1="";
						$_data->option_price="";
					}
			?>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/product_register_stitle1.gif" ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr><td height="3"></td></tr>
					<tr>
						<td style="padding-bottom:3pt;">
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/distribute_01.gif"></TD>
							<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
							<TD><IMG SRC="images/distribute_03.gif"></TD>
						</TR>
						<TR>
							<TD background="images/distribute_04.gif"></TD>
							<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
							<TD width="100%" class="notice_blue"><img src="images/icon_point2.gif" width="8" height="11" border="0"> <span class="font_orange"><b>�ʼ�ǥ�� �׸�</b></span></TD>
							<TD background="images/distribute_07.gif"></TD>
						</TR>
						<TR>
							<TD><IMG SRC="images/distribute_08.gif"></TD>
							<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
							<TD><IMG SRC="images/distribute_10.gif"></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr><td height=3></td></tr>
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<col width=110></col>
						<col width=240></col>
						<TR>
							<TD colspan=4 background="images/table_top_line.gif"></TD>
						</TR>
						<!-- �뿩 ��ǰ ( ��ǰ ���� ) -->
						<? //include("product_register.add.rent.php"); 						
						$categoryRentInfo = categoryRentInfo($code);					
						$rentProduct = rentProduct($_data->pridx);
						if(_array($rentProduct) ){
							//_pr($rentProduct);
							//$goodsTypeSel['2'] = "checked";
							$itemTypeSel[$rentProduct['itemType']] = "checked";						
						} else {
							//$goodsTypeSel['1'] = "checked";
							$itemTypeSel['product'] = "checked";
						}
						
						if($_data->rental == '2') $goodsTypeSel[$_data->rental] = "checked";
						else $goodsTypeSel[1] = "checked";
						?>
						<TR>
							<TD colspan="4" style="height:1px" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ ����</TD>
							<TD class="td_con1" colspan="3" style="padding:0px;">
							<script language="javascript" type="text/javascript">
							function toggleGoodsType(val){
								if(val == '2'){ // ��Ż ��ǰ
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
									<? echo ($_data->rental=='2')?'�뿩��ǰ':'�ǸŻ�ǰ'; ?><a href="javascript:document.location.reload()">[Refresh]</a>
								<?	}else{ ?>
									<input type=radio id="goodsType1" name="goodsType" value="1" <?=$goodsTypeSel['1']?> onclick="toggleGoodsType('1');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType1>�ǸŻ�ǰ</label> &nbsp;
									<? if(_array($categoryRentInfo)){ ?>
									<input type=radio id="goodsType2" name="goodsType" value="2" <?=$goodsTypeSel['2']?> onclick="toggleGoodsType('2'); "><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType2>�뿩��ǰ</label><a href="javascript:document.location.reload()"><? } ?></a>
								<?	} ?>								
								</div>
							</TD>
						</TR>
						
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>

						<? if($_data->vender>0){?>
						<tr>
							<td class="table_cell">��Ͼ�ü</td>
							<td class="td_con1" colspan="3">
							<?
							$sql = "SELECT vender,id,brand_name FROM tblvenderstore WHERE vender='".$_data->vender."' ";
							$result=mysql_query($sql,get_db_conn());
							if($row=mysql_fetch_object($result)) {
								echo "<A HREF=\"javascript:viewVenderInfo(".$row->vender.")\"><B>".$row->brand_name." (".$row->id.")</B></A>";
							}
							mysql_free_result($result);
							//���� ���� ������ ��Ƶδ� �κ�
							$vender = $row->vender;
							$vender_name = $row->brand_name;
							$vender_id = $row->id;
							?>
							</td>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>

						<? }?>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">���/������</TD>
							<TD class="td_con1" colspan="3">
			<?
						if (strlen($prcode)==0) {
							echo "�ڵ��Է�";
						} else {
							if ($_data) {
								echo " ".str_replace("-","/",substr($_data->modifydate,0,16))."\n";
								echo "(��ǰ�ڵ� : <span class=\"font_orange\">".$_data->productcode."</span>)";
								echo "&nbsp;&nbsp;&nbsp;<a href=\"http://".$shopurl."?productcode=".$_data->productcode."\" target=_blank><img src=\"images/productregister_goproduct.gif\" align=absmiddle border=0></font></a>";
							}
							echo "<input type=hidden name=productcode value=\"".$_data->productcode."\">\n";
						}
			?>
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">���� ī�װ�</TD>
							<TD class="td_con1" colspan="3" style="word-break:break-all;">
			<?
							$code_loc = "";
							$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
							if(substr($code,3,3)!="000") {
								$sql.= "AND (codeB='".substr($code,3,3)."' OR codeB='000') ";
								if(substr($code,6,3)!="000") {
									$sql.= "AND (codeC='".substr($code,6,3)."' OR codeC='000') ";
									if(substr($code,9,3)!="000") {
										$sql.= "AND (codeD='".substr($code,9,3)."' OR codeD='000') ";
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
							//echo $sql; exit;
							$result=mysql_query($sql,get_db_conn());
							$i=0;
							while($row=mysql_fetch_object($result)) {
								if($i>0) $code_loc.= " > ";
								$code_loc.= $row->code_name;
								$i++;
							}
							mysql_free_result($result);

							if (strlen($prcode)>0) {
								echo $code_loc." > <B><span class=\"font_orange\">".$productname."</B></span>";
							} else {
								echo $code_loc." > <B><span class=\"font_orange\">".($gongtype=="Y"?"�������� �ű��Է�":"�ű��Է�")."</B></span>";
							}
			?>
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>

			<? /*
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�߰� ����ī�װ�</TD>
							<TD class="td_con1" colspan="3">
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
											<a href='javascript:void(0)' onClick='cateDel(this.parentNode.parentNode)'><input type='button' value='����' align=absmiddle></a>
										</td>
									</tr>
									<?
									}
									?>
								</table>
								<select name="codeA" style="width:200px" onchange="SearchChangeCate(this,1);">
									<option value="">--- 1�� ī�װ� ���� ---</option>
								</SELECT>
								<select name="codeB" style="width:200px" onchange="SearchChangeCate(this,2);">
									<option value="">--- 2�� ī�װ� ���� ---</option>
								</SELECT>
								<select name="codeC" style="width:200px" onchange="SearchChangeCate(this,3);">
									<option value="">--- 3�� ī�װ� ���� ---</option>
								</SELECT>
								<select name="codeD" style="width:200px">
									<option value="">--- 4�� ī�װ� ���� ---</option>
								</SELECT>
								<input type="button" value="�߰�" onclick="javascript:cateAdd()">
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						*/ ?>
						
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ��������</TD>
							<TD class="td_con1"><input type=radio id="idx_display1" name=display value="Y" <? if ($_data) { if ($_data->display=="Y") echo "checked"; } else echo "checked";  ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display1>������</label> &nbsp; <input type=radio id="idx_display2" name=display value="N" <? if ($_data) { if ($_data->display=="N") echo "checked"; } ?> onclick="JavaScript:alert('���� ȭ���� ��ǰ Ư¡�� �������� ����˴ϴ�.')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display2>��������</label></TD>
						</TR>
						
<? /*
						<!-- ���� �Ǹ� ��ǰ ���� -->
						<?
							$reservation = "";
							$reservationDate = "";
							if( $_data->reservation != "0000-00-00" AND strlen($_data->reservation) > 0  ) {
								$reservation = "Y";
								$reservationDate = $_data->reservation;
							}
						?>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR  class="productItemArea">
							<TD class="table_cell" class=""><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>���� �Ǹ� ��ǰ</b></TD>
							<TD class="td_con1" colspan="3">
								<input type=checkbox id="reservation" name="reservation" value="Y" <?=($reservation=="Y")?"checked":"";?> onchange="reservationDiv.style.display=(this.checked==true?'block':'none');">
								<label style='cursor:hand;' onmouseover="style.reservation='underline'" onmouseout="style.reservation='none'" for=reservation>���� �Ǹ� ��ǰ ���</label>&nbsp;<span class="font_orange">
								<DIV style="display:<?=($reservation=="Y")?"block":"none";?>;" id="reservationDiv">
									��� ������ : <input type=text name=reservationDate value="<?=$reservationDate?>" size=12 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
								</DIV>
							</TD>
						</TR>

						<TR  class="productItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						*/ ?>
						<TR>
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b><span class="font_orange">��ǰ��</span></b></TD>
							<TD class="td_con1" colspan="3"><input name=productname value="<?=ereg_replace("\"","&quot",$_data->productname)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" class="input" style="width:100%"></TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰȫ������</TD>
							<TD class="td_con1" colspan="3"><input name="prmsg" value="<?=ereg_replace("\"","&quot",$_data->prmsg)?>" size=80 maxlength=250 onKeyDown="chkFieldMaxLen(250)" class="input" style="width:100%"></TD>

						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<?
						$usevender = getVenderUsed();
						if($usevender[OK] == "OK"){ 
						/*
						?>
						<!-- vender ���� �߰�::���θ��� ��쿡�� ������� �����ؾ��� -->
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ����</TD>
							<TD class="td_con1" colspan="3">
								������ :
								<select name="vender_name" style="width:260px" class="input">
									<option value=''>����</option>";
									<?
										$venderResult = mysql_query( "SELECT `id` FROM `tblvenderinfo` Order By `id` ASC; ",get_db_conn());
										while ( $venderRow = mysql_fetch_assoc ( $venderResult ) ) {
											$sel = ($venderRow['id'] == $vender_id)?"selected":"";
											echo "<option value='".$venderRow['id']."' ".$sel.">".$venderRow['id']."</option>";
										}
									?>
								</select>

							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<? */
						?>
						<TR class="rentalItemArea">
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ������ �� ����</TD>
							<TD class="td_con1" colspan="3">
						<?	if($_data->vender > 0){							
							$commi = rentCommitionByCategory($code,$_data->vender);
						?>
							
								<div style="margin-bottom:5px;"><input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>   />�������� (������  <?=number_format($commi['self'])?>%)<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  />��Ź���� (������  <?=number_format($commi['main'])?>%)<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> />��Ź���δ��</div>
						<?  } ?>								
							<div id="goodsTypeLocalDiv" class="rentalItemArea">	
							<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
								<tr>
									<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��ǰ Ÿ��</th>
									<td style="text-align:left;padding-left:10px;">
										<input type=radio id="itemType1" name="itemType" value="product" <?=$itemTypeSel['product']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>��ǰ</label> &nbsp;
										<input type=radio id="itemType2" name="itemType" value="location" <?=$itemTypeSel['location']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>���</label> &nbsp;
									</td>
									<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
									<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>���ݱ���</th>
									<td style="text-align:left;padding:0px 10px;">
										<? switch($categoryRentInfo['pricetype']){
												case 'time': echo '�ð����� ���'; break;
												case 'day': echo '�Ϸ�(24�ð�)���� ���'; break;
												case 'checkout': echo '������(����2��~����11��) ���'; break;
												default: echo '����'; break;
										} ?>
									</td>
									<? } ?>									
									<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��������</th>
									<td style="text-align:left;padding:0px 10px;">
										<? echo ($categoryRentInfo['useseason'] == '1')?'������ ���':'������';?>
									</td>
								
								<?  /* if(!_empty($_data->pricetime)){ ?>
									<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>���ݱ���</th>
									<td style="text-align:left;padding-left:10px;">
										<? switch($_data->pricetime){
												case '1': echo '�ð����� ���'; break;
												case '24': echo '�Ϸ�(24�ð�)���� ���'; break;
												case 'checkout': echo '������(����2��~����11��) ���'; break;
												default: echo '����'; break;
										} ?>
									</td>
								<? } */ ?>
								</tr>
							</table>
							
							<div style="margin:10px 0px;overflow:hidden;">
								<h6 style="float:left;padding-top:5px;font-size:13px;font-weight:700;letter-spacing:-1px;">* ��ǰ ����� �� �뿩 ��� ����</h6>
								<div style="float:right;">
									<input type="button" value="����/��Ż ��Ȳ����" onclick="bookingSchedulePop(<?=$_data->pridx?>,'1');">
									<input type="button" value="�����԰�" onclick="bookingRepair(<?=$_data->pridx?>);">
									<? /* <input type="button" value="���������" onclick="bookingProductConnPop(<?=$_data->pridx?>);"> */ ?>
								</div>				
							</div>
							<?
							// �뿩 ����� ���� ����Ʈ	
							$value = array("display"=>1,'vender'=>(($rentProduct['istrust'] == '1')?$_data->vender:0)); // ���� �� ǥ��
							$localList = rentLocalList( $value );
							if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
							?>
							<!-- ����Ʈ --->			
							<table border="0" cellpadding="0" cellspacing="0" class="tableBase" style="clear:both">
								<tr align="center">
									<th style="width:60px;" class="firstTh">�����ڵ�</th>
									<th style="width:60px;">Ÿ��</th>
				<!-- 					<th>����������</th> -->
									<th style="width:200px;">����</th>
				<!--					<th>����</th> -->
									<th style="width:700px">�ּ�</th>
									<th style="width:40px;">����</th>
								</tr>
								<tr>
									<td colspan="4" align="center" class="firstTd">����� ���� ����</td>
									<td align="center"><input type="radio" value="0" name="location" <? if(!_isInt($rentProduct['location'])) echo 'checked="checked"';?> /></td>
								</tr>
								<? foreach ( $localList as $k=>$v ) { ?>
								<tr>
									<td class="firstTd" align="center"><?=$v['location']?></td>
									<td align="center"><?=rentProduct::locationType($v['type'])?></td>
				<!--					<td align="center"><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "����"); ?></td> -->
									<td style="padding-left:10px;"><?=$v['title']?></td>
				<!--					<td style="padding-left:10px;"><?=$v['ypos']?>*<?=$v['xpos']?></td> -->
									<td style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
									<td align="center"><input type="radio" value="<?=$v['location']?>" name="location" <? if($rentProduct['location'] == $v['location']) echo 'checked="checked"';?> /></td>
								</tr>
								<? } ?>
							</table>			
							<?
							if ( retnOptionUseCnt($_data->pridx) == 0 ) {
								echo "<span style=\"color:#ec2f36;\"><strong>�ɼ��� �ּ� 1�� �̻� �Է��� �ּ���!</strong></span>";
							}
				
							?>
							<div style="margin-top:10px;">
							<?	if(_isInt($_data->pridx)){ ?>
								<input type="button" value="�뿩��ǰ�ɼ�" onclick="rentProdOptManager(<?=$_data->pridx?>);">
							<? }else{ ?>
								�뿩��ǰ �ɼ��� ��ǰ ���� ���� ������ ������ ���ؼ� ó�� �����մϴ�.
							<? } ?>
							</div>
						</div>
							</TD>
						</TR>
						<TR class="rentalItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						
						<?	if($_data->vender > 0){  ?>
						<TR class="rentalItemArea">
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������̵���</TD>
							<TD class="td_con1" colspan="3">
							<? if($rentProduct['istrust'] == '1') echo '����';
							else{ ?>
							<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />����
							<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />�����
						<?	}?>
							</TD>
						</TR>
						<TR class="rentalItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR class="rentalItemArea">
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�̴�Ȩ����</TD>
							<TD class="td_con1" colspan="3">
							<? if($rentProduct['istrust'] == '1') echo '��';
							else{ ?>
							<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />��
							<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />�ƴϿ�
						<?	}?>
							</TD>
						</TR>
						<TR class="rentalItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>		
						<?  } ?>
						
					<?	
					} ?>
						
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ ��ϳ�¥</TD>
							<TD class="td_con1" colspan="3"><input type=checkbox id="idx_insertdate10" name=insertdate1 value="Y" onclick="DateFixAll(this)" <?=($insertdate_cook=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate10>��� ���� ����</label>&nbsp;<span class="font_orange">(* ��ǰ������ ��ϳ�¥�� ������� �ʽ��ϴ�.)</span></TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">���� �� ��������</TD>
							<TD class="td_con1" colspan="3">
								<input type=checkbox id="idx_etcapply_coupon" name=etcapply_coupon value="Y" <?=($_data->etcapply_coupon=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_coupon>�������� ����Ұ�</label>
								&nbsp;&nbsp;&nbsp;
								<input type=checkbox id="idx_etcapply_reserve" name=etcapply_reserve value="Y" <?=($_data->etcapply_reserve=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_reserve>������ ���Ұ�</label>
								&nbsp;&nbsp;&nbsp;
								<input type=checkbox id="idx_etcapply_gift" name=etcapply_gift value="Y" <?=($_data->etcapply_gift=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_gift>���Ż���ǰ ����Ұ�</label>
								<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($_data->etcapply_return=="Y")?"checked":"";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>��ȯ �� ȯ�� �Ұ�</label>
								<input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>���ݰ����� ����ϱ�</label> <span class="font_orange">(���� ��ǰ�� �Բ� ���Ž� ������ ���ݰ����θ� ����˴ϴ�.)</span>
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<?// if($_data->vender>0){ ?>
						<? /*
						<input type=hidden name="assembleuse" value="N">
						<TR>
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><span class="font_orange"><b><?=($gongtype=="Y"?"������":"�ǸŰ���")?></b></span></TD>
							<TD class="td_con1"><input name=sellprice value="<?=$_data->sellprice?>" size=16 maxlength=10 class="input" style=width:98%></TD>
							<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point2.gif" width="8" height="11" border="0"><span class="font_orange"><b><?=($gongtype=="Y"?"���۰�":"���߰���")?></b></span></TD>
							<TD class="td_con1"><input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style=width:100%><br><span class="font_orange">* <strike>5,000</strike>�� ǥ���, 0 �Է½� ǥ��ȵ�&nbsp;</span></TD>
						</tr>
						*/ ?>
						<?// } else { ?>

						<TR>
							<?	//if(strlen($prcode)==0) { ?>
							<!-- <TD class="table_cell">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<td rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0"></td>
								<td width="100%"><span class="font_orange"><b>�ǸŰ���</b></span></label></td>
							</tr> -->
							<!--
							<tr>
								<td rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0"></td>
								<td width="100%"><input type="radio" name="assembleuse" value="N" <?=($_data->assembleuse=="Y"?"":"checked")?> id="idx_assembleuseY" style="border:none" onclick="assembleuse_change();"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_assembleuseY"><span class="font_orange"><b><?=($gongtype=="Y"?"���� ������":"���� �ǸŰ���")?></b></span></label></td>
							</tr>
							<tr>
								<td width="100%"><input type="radio" name="assembleuse" value="Y" <?=($_data->assembleuse=="Y"?"checked":"")?> id="idx_assembleuseN" style="border:none" onclick="assembleuse_change();"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_assembleuseN"><span class="font_orange"><b><?=($gongtype=="Y"?"�ڵ�/���� �ǸŰ�":"�ڵ�/���� �ǸŰ�")?></b></span></label></td>
							</tr>
							<tr>
								<td width="100%">&nbsp;&nbsp;&nbsp;<span class="font_orange" style="font-size:8pt;">* �ѹ� ����� ����Ұ�</span></td>
							</tr>
							 -->
							<!-- </table> -->
							<!-- </TD> -->
							<?// } else { ?>
							<TD class="table_cell">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<input type=hidden name="assembleuse" value="<?=$_data->assembleuse?>">
							<tr>
								<td><img src="images/icon_point2.gif" width="8" height="11" border="0"></td>
								<td width="100%" height="30">
								<?	if($_data->assembleuse=="Y") { ?>
									<!-- <span class="font_orange"><b><?=($gongtype=="Y"?"�ڵ�/���� �ǸŰ�":"�ڵ�/���� �ǸŰ�")?></b></span> -->
								<? } else { ?>
									<span class="font_orange"><b><?=($gongtype=="Y"?"���� ������":"���� �ǸŰ���")?></b></span>
								<? } ?>
								</td>
							</tr>
							</table>
							</TD>
							<? // } ?>
							<TD colspan="3" class="td_con1">

								�ǸŰ� : <input name=sellprice value="<?=(int)(strlen($_data->sellprice)>0?$_data->sellprice:"0")?>" size=16 maxlength=10 class="input" <?=($_data->assembleuse=="Y"?"disabled style='background:#C0C0C0'":"")?> style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('sell');" onfocus="sellpriceAutoCalc('sell');">��
								=
								���� : <input name=consumerprice value="<?=(int)(strlen($_data->consumerprice)>0?$_data->consumerprice:"0")?>" size=16 maxlength=10 class="input" style="text-align:center; font-weight:bold; width:80px;" onkeyup="sellpriceAutoCalc('org');" onfocus="sellpriceAutoCalc('org');" >��
								-
								������ : <input name=discountRate value="<?=(int)(strlen($_data->discountRate)>0?$_data->discountRate:"0")?>" size=3 maxlength=3 class="input" style="text-align:center; font-weight:bold; width:40px;" onkeyup="sellpriceAutoCalc('disc');">%
								(<input type="checkbox" id="autoCalc"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="autoCalc">�ڵ����</label>)

								<br><span class="font_orange">* ���� <strike>5,000</strike>�� ǥ���, 0 �Է½� ǥ��ȵ�.&nbsp;</span>
								<br><span class="font_orange">* ��Ż��ǰ�� ��� ��ǰ�ɼǰ����� �������� �����˴ϴ�.&nbsp;</span>

								<?
								/*
									if ($_data->pridx) {
										$setSeasonInfo = rentProductSeasonPrice($_data->pridx);
									} else {
										$setSeasonInfo['busySeason'] = 0 ;
										$setSeasonInfo['semiBusySeason'] = 0 ;
										$setSeasonInfo['holidaySeason'] = 0 ;
									}
								?>
								<br>������ �߰� �ݾ� : <input type="text" value="<?=$setSeasonInfo['busySeason']?>" name="busySeason">��
								<br>�ؼ����� �߰� �ݾ� : <input type="text" value="<?=$setSeasonInfo['semiBusySeason']?>" name="semiBusySeason">��
								<br>�ָ� �߰� �ݾ� : <input type="text" value="<?=$setSeasonInfo['holidaySeason']?>" name="holidaySeason">��
 								*/
								?>
							</TD>
						</tr>
						<? //} ?>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<? /* ���� ���� �߰� jdy */?>
						<TR>
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b><span class="font_orange">��������</span></b></TD>
							<TD class="td_con1" colspan="3"><input type=radio id="tax_yn1" name="tax_yn" value="0" <? if ($_data) { if ($_data->tax_yn=="" || $_data->tax_yn=="0") echo "checked"; } else echo "checked";  ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=tax_yn1>�Ϲݰ���</label> &nbsp; <input type=radio id="tax_yn2" name="tax_yn" value="1" <? if ($_data) { if ($_data->tax_yn=="1") echo "checked"; } ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=tax_yn2>�����</label></TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<? /* ���� ���� �߰� jdy */?>
						<TR class="productItemArea">
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b><span class="font_orange">���Ű���</span></b></TD>
							<TD class="td_con1" colspan="3"><input name="productdisprice" value="<?=ereg_replace("\"","&quot",$_data->productdisprice)?>" size=20 maxlength=50 onKeyDown="chkFieldMaxLen(50)" class="input" style="width:20%"></TD>
						</TR>
						<TR class="productItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<? /****** ������ ���� ���� jdy ************/?>

						<? if ($_data->productcode) {
							//������ ���. ��ü�� ������ ������ ��ȸ

								$vender_more = getVenderMoreInfo($_data->vender);
								$commission_type = $vender_more['commission_type'];

								if ($account_rule=="1" || $commission_type=="1") {
								//���ް��� ��ǰų�.. ���������� ���� ��������?�� ��Ÿ��.
								?>
								<TR class="productItemArea">
									<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b><span class="font_orange"><?= $adjust_title ?></span></b></TD>
									<TD class="td_con1" colspan="3">
									<? if ($account_rule=="1") {

											$cf_num = $_data->cf_cost;
											$rq_num = $_data->rq_cost;
											if ($cf_num=="") $cf_num="0";

											if ($_data->first_approval=="1") {

												$adjust = $_data->sellprice - $cf_num;

												$com_status = "<b>���� ���ް� <span class=\"font_blue\">".$cf_num."</span>�� (������ ".$adjust."��)</b>";
											}else{
												$com_status = "<b>���δ��</b>";
											}

											if ($_data->status == '') {
												$com_status .= "";
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
										<span class="font_orange">* ������ = �ǸŰ� - ���λ�ǰ���ް�</span>
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
												$com_status .= "";
											}else if ($_data->status == '1') {
												$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>������ ��û�� <span class=\"font_blue\">".$rq_num."</span>%</b>";
											}else if ($_data->status == '3') {
												$com_status .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>������ ��û�ź� <span class=\"font_blue\">".$rq_num."</span>%</b>";
											}

											$com_title = "������";
											$com_input = "<input type=text name=up_rq_com value=\"\" size=3 maxlength=3 onkeyup=\"strnumkeyup(this)\" class=input>%";

										?>
										<?= $com_status ?>
									<? } ?>
									&nbsp;&nbsp;&nbsp;&nbsp;<button style="color:#ffffff;background-color:#000000;border:0;width:50px;height:25px;cursor:pointer" onclick="commissionDivView();">����</button>
									<? if (!$_data->status) { ?>
									&nbsp;&nbsp;<span style="color:red;font-weight:bold">* <?= $adjust_title ?>�� �������� �ʾҽ��ϴ�. <?= $adjust_title ?>�� �������ּ���.</span>
									<? } ?>
									<br/>
									<div id="commission_div" style="position:absolute;width:450px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;display:none;margin-top:10px;">
										<div style="width:100%;text-align:right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionDivView('N');" >X</span></div>
										<div style="width:100%;margin-top:5px;">
											<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
												<col width=100 />
												<col width= />
												<col width=100 />
											<tr><td height=2 colspan="3" bgcolor=#808080></td></tr>
												<? if ($_data->status == '1') {?>
												<tr>
													<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��û <?= $com_title ?></td>
													<td style=padding:7,10>
														<?= $rq_num ?>
														<? if ($account_rule=="1") { ?>
														��
														<? }else {?>
														%
														<? }?>
														<input type="hidden" name="commission_result" />
													</td>
													<td align="right">
														<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionOk('Y')">����</span>&nbsp;
														<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionOk('N')">�ź�</span>
													</td>
												</tr>
												<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>
												<? } ?>
												<tr>
													<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><?= $com_title ?></td>
													<td style=padding:7,10>
														<?= $com_input ?>
													</td>
													<td align="right">
														<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionChange()">����</span>
													</td>
												</tr>
												<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>
											</table>
										</div>
									</div>

									</TD>
								</TR>
								<TR productItemArea>
									<TD colspan="4" background="images/table_con_line.gif"></TD>
								</TR>
								<?
								}
						}else {
							//�Է��� ��� �׳� �Է��� ����
						?>
						<TR class="productItemArea">
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b><span class="font_orange"><?= $adjust_title ?></span></b></TD>
							<TD class="td_con1" colspan="3">
							<? if ($account_rule=="1") { ?>
								<input type="text" size="10" class="input" name="up_rq_cost" id="up_rq_cost"/> �� (��ǰ ���ް��� �Է����ּ���.)
								<br/>
								<span class="font_orange">* ������ = �ǸŰ� - ���λ�ǰ���ް�</span>
							<? }else{ ?>
								<input type="text" size="10" class="input" name="up_rq_com" id="up_rq_com"/> %
								<br/>
								<span class="font_orange">* ��ü��ǰ ���� �������� ��� �Է��ص� ������� �ʽ��ϴ�.</span>
							<? } ?>
							</TD>
						</TR>
						<TR class="productItemArea">
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<?
						}
						/****** ������ ���� ���� jdy ************/?>

						<? if ($gongtype=="N") { ?>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ǸŰ��� ��ü����</TD>
							<TD class="td_con1" colspan="3">
							<input type=checkbox id="idx_dicker1" name=dicker value="Y" <? if ($_data) { if ($dicker=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dicker1><b>�����</b></label> &nbsp;<input type=text name=dicker_text value="<?=$dicker_text?>" size=20 maxlength=20 onKeyDown="chkFieldMaxLen(20)" class="input"> <span class="font_orange">* ��) �ǸŴ���ǰ, ��㹮��(000-000-000)</span><br /><!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b>�� ��ǰ �ǸŰ��� ��� ���ϴ� ������ ��½�Ű�� ����Դϴ�.<br> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> �Է°��� ���� ���� �ѱ� 10��, ���� 20�ڷ� ���ѵǾ� �ֽ��ϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> ���� �ֹ��� ������� �ʽ��ϴ�.
							<? } ?>

							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������(��)</TD>
							<TD class="td_con1" colspan="3"><input name=reserve value="<?=$_data->reserve?>" size=16 maxlength=6 class="input" style="width:60%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);"> <select name="reservetype" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N"<?=($_data->reservetype!="Y"?" selected":"")?>>������(��)</option><option value="Y"<?=($_data->reservetype!="Y"?"":" selected")?>>������(%)</option></select><br><span class="font_orange" style="font-size:8pt;letter-spacing:-0.5pt">* �������� �Ҽ��� ��°�ڸ����� �Է� �����մϴ�.<br>* �������� ���� ���� �ݾ� �Ҽ��� �ڸ��� �ݿø�.</span>
							</TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>


						<tr>
							<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">���Կ���</TD>
							<TD class="td_con1" colspan="3"><input name=buyprice value="<?=$_data->buyprice?>" size=16 maxlength=10 class="input" style=width:100%></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������</TD>
							<TD class="td_con1"><input name=production value="<?=$_data->production?>" size=23 maxlength=20 onKeyDown="chkFieldMaxLen(50)" class="input"><a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></TD>
							<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">������</TD>
							<TD class="td_con1"><input name=madein value="<?=$_data->madein?>" size=23 maxlength=20 onKeyDown="chkFieldMaxLen(30)" class="input"><a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�귣��</TD>
							<TD class="td_con1"><input type=text name=brandname value="<?=$_data->brandname?>" size=23 maxlength=50 onKeyDown="chkFieldMaxLen(50)" class="input"><a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a><br>
							<span class="font_orange">* �귣�带 ���� �Է½ÿ��� ��ϵ˴ϴ�.</span></TD>
							<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">�𵨸�</TD>
							<TD class="td_con1"><input name=model value="<?=$_data->model?>" size=23 maxlength=40 onKeyDown="chkFieldMaxLen(50)" class="input"><a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell" rowspan="2"><img src="images/icon_point5.gif" width="8" height="11" border="0">�����ڵ�</TD>
							<TD class="td_con1"><input name=selfcode value="<?=$_data->selfcode?>" size=35 maxlength=20 onKeyDown="chkFieldMaxLen(20)" class="input" style=width:100%></td>
						</tr>
						<tr>
							<TD class="td_con1" colspan="3">
							<span class="font_orange">* ���θ����� �ڵ����� �߱޵Ǵ� ��ǰ�ڵ�ʹ� ������ ��� �ʿ��� ��ü��ǰ�ڵ带 �Է��� �ּ���.<br>
							* �����ڵ� ���� ������ <a href="javascript:parent.parent.topframe.GoMenu(1,'shop_productshow.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ��ǰ ���� ��Ÿ ����</a></span> ������ �� �ֽ��ϴ�.
							</span></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�����</TD>
							<TD class="td_con1" colspan="3"><input name=opendate value="<?=$_data->opendate?>" size=20 maxlength=8 class="input">&nbsp;&nbsp;��) <?=DATE("Ymd")?>(��ó����)<br>
							<span class="font_orange">* ���ݺ� ������ �� ���޾�ü ���� ����� ���˴ϴ�.<br>* �߸��� ����� �������� ���� ������ �������� å�����ž� �˴ϴ�.</span></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0"><b><span class="font_orange">����</span></b></TD>
							<TD class="td_con1" colspan="3">
			<?
						if ($gongtype=="Y") {
							if ($_data) {
								$quantity=$_data->quantity;
								if($_data->quantity<=0) $checkquantity="E";
								else $checkquantity="C";
							}else {
								$checkquantity="C";
							}

							$arrayname= array("����","����");
							$arrayprice=array("E","C");
							$arraydisable=array("true","false");
							$arraybg=array("silver","white");
							$arrayquantity=array("","$quantity");
							for($i=0;$i<2;$i++){
								echo "<input type=radio id=\"idx_checkquantity".$i."\" name=checkquantity value=\"".$arrayprice[$i]."\" ";
								if($checkquantity==$arrayprice[$i]) echo "checked "; echo "onClick=\"document.form1.quantity.disabled=".$arraydisable[$i].";document.form1.quantity.style.background='".$arraybg[$i]."';document.form1.quantity.value='".$arrayquantity[$i]."';\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_checkquantity".$i.">".$arrayname[$i]."</label>&nbsp;";
							}
							echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\" class=\"input\">��";
						} else {
							if ($_data) {
								$quantity=$_data->quantity;
								if($_data->quantity==NULL) $checkquantity="F";
								else if($_data->quantity<=0) $checkquantity="E";
								else $checkquantity="C";
								if($quantity<0) $quantity="";
							} else {
								$checkquantity="F";
							}

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
							echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\" class=\"input\">��";
						}
						if($checkquantity=="C"){
							echo "<script>document.form1.quantity.disabled=false;document.form1.quantity.style.background='white';</script>\n";
						}else{
							echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
						}
			?>
							</TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ּұ��ż���</TD>
							<TD class="td_con1"><input type=text name=miniq value="<?=($miniq>0?$miniq:"1")?>" size=5 maxlength=5 class="input"> �� �̻�</TD>
							<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ִ뱸�ż���</TD>
							<TD class="td_con1"><input type=radio id="idx_checkmaxq1" name=checkmaxq value="A" <? if (strlen($maxq)==0 || $maxq=="?") echo "checked ";?> onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq1>������</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_checkmaxq2" name=checkmaxq value="B" <? if ($maxq!="?" && $maxq>0) echo "checked"; ?> onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq2>����</label> : <input name=maxq size=5 maxlength=5 value="<?=$maxq?>" class="input"> �� ����
							<script>
							if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
							else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
							</script>
							</TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������ۺ�</td>
							<td class="td_con1" colspan="3">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<td><input type=radio id="idx_deliprtype0" name=deli value="H" <? if($_data->deli_price<=0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype0>�⺻ ��ۺ� <b>����</b></label>
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
								<td><input type=radio id="idx_deliprtype3" name=deli value="N" <?if($_data->deli_price>0 && $_data->deli=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype3>���� ��ۺ� <b><font color="#FF0000">����</font></b> <input type=text name=deli_price_value1 value="<?if($_data->deli_price>0 && $_data->deli=="N") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="Y") echo "disabled style='background:silver'";?> class="input">��</label>&nbsp;<a href="javascript:deli_helpshow();"><img src="images/product_optionhelp3.gif" border="0" align="absmiddle"></a>
									<br>
									<input type=radio id="idx_deliprtype4" name=deli value="Y" <?if($_data->deli_price>0 && $_data->deli=="Y") echo "checked";?> onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype4>���� ��ۺ� <b><font color="#FF0000">����</font></b> <input type=text name=deli_price_value2 value="<?if($_data->deli_price>0 && $_data->deli=="Y") echo $_data->deli_price;?>" size=6 maxlength=6 <?if($_data->deli_price<=0 || $_data->deli=="N") echo "disabled style='background:silver'";?> class="input">�� (���ż� ��� ���� ��ۺ� ���� : <FONT COLOR="#FF0000"><B>��ǰ���ż������� ��ۺ�</B></font>)</label>&nbsp;<a href="javascript:deli_helpshow();"><img src="images/product_optionhelp3.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
							<tr id="deli_helpshow_idx" style="display:none;">
								<td style="padding:5px;">
								<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
								<TR>
									<TD><IMG SRC="images/distribute_01.gif"></TD>
									<TD background="images/distribute_02.gif"></TD>
									<TD><IMG SRC="images/distribute_03.gif"></TD>
								</TR>
								<TR>
									<TD background="images/distribute_04.gif"></TD>
									<TD class="notice_blue" valign="top">
									<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
									<TR>
										<TD class="notice_blue" valign="top" width="745" colspan="2"><IMG SRC="images/distribute_img1.gif" width="110" height="35" ></TD>
									</TR>
									<TR>
										<TD class="notice_blue" valign="top">&nbsp;</TD>
										<TD width="100%" class="space"><span class=font_blue>&nbsp;&nbsp;&nbsp;&nbsp;<b>'������ۺ�' �Է� �� '��ۺ� Ÿ�� ��ǰ�� ��� ��ۺ� ����' <font color='#0000FF'>üũ</font> ��)</b><br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���Ű���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 10,000�� �� 2������ = ��ǰ���� 20,000��<br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ۺ�&nbsp;&nbsp;: 3,000�� �϶� �� 2������= �ѹ�ۺ� 6,000��<br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� �����ݾ� : 26,000��<br><br>
										&nbsp;&nbsp;&nbsp;&nbsp;<b>'������ۺ�' �Է� �� '��ۺ� Ÿ�� ��ǰ�� ��� ��ۺ� ����' <font color='#FF0000'>��üũ</font> ��)</b><br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���Ű���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 10,000�� �� 2������ = ��ǰ���� 20,000��<br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ۺ�&nbsp;&nbsp;: 3,000��(���ż��� 2���� 3,000�� �ѹ��� ����)<br>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� �����ݾ� : 23,000��</span></TD>
									</TR>
									</TABLE>
									</TD>
									<TD background="images/distribute_07.gif"></TD>
								</TR>
								<TR>
									<TD><IMG SRC="images/distribute_08.gif"></TD>
									<TD background="images/distribute_09.gif"></TD>
									<TD><IMG SRC="images/distribute_10.gif"></TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ������</td>
							<td class="td_con1" colspan="3">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<!-- <td><input type=radio id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($_data->group_check!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">��ǰ������ ������</label>&nbsp;&nbsp;<span class="font_orange">* ��ǰ������ �������� ��� ��� ��ȸ��, ȸ������ ����˴ϴ�.</span><br><input type=radio id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($_data->group_check=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">��ǰ������ ����</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ȸ������� <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">ȸ������ > ȸ����� ���� > ȸ����� ���/����/����</span></a>���� �����ϼ���.</span></td> -->
								<td><input type=radio id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($_data->group_check!="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">��ǰ������ ������</label>&nbsp;&nbsp;<span class="font_orange">* ��ǰ������ �������� ��� ��� ��ȸ��, ȸ������ ����˴ϴ�.</span><br><input type=radio id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($_data->group_check=="Y") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">��ǰ������ ����</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ȸ������� <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">ȸ������ > ȸ����� ���� > ȸ����� ���/����/����</span></a>���� �����ϼ���.</span></td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<tr id="group_checkidx" <?if($_data->group_check!="Y") echo "style=\"display:none;\"";?>>
								<td>
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
									<td bgcolor="#FFF7F0" style="border:2px #FF7100 solid;">
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
										echo "<td width=\"25%\" style=\"padding:3px;\"><input type=checkbox id=\"group_code_idx".$grpcnt."\" name=\"group_sel_code[]\" value=\"".$rowgrp->group_code."\" ".(strlen($group_code[$rowgrp->group_code])>0?"checked":"")."> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"group_code_idx".$grpcnt."\">".$rowgrp->group_name."</label></td>\n";
										$grpcnt++;
									}
									mysql_free_result($resultgrp);

									if($grpcnt==0) {
										echo "<td style=\"padding:3px;\">* ȸ������� �������� �ʽ��ϴ�.<br>* ȸ������� <a href=\"javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');\"><span class=\"font_blue\">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �ŷ�ó ����</span></a>���� ����ϼ���.</span></td>\n";
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
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>


						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ�������</TD>
							<TD colspan="3">
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
												.dtitleTd{ padding:0px 0px 0px 10px; background-color:#f5f5f5; }
												.daccTd{ padding:8px 0px 8px 10px; }
												.dbtnTd{ padding:10px 0px 10px 0px; }
												.dtitleInput{ width:96%; border:1px solid #ccc; font-family:����; letter-spacing:-1px; }
												.ditemTextarea{ width:98%; line-height:18px;}
											</style>

											<script language="javascript" type="text/javascript">

												function addGosiItem(el,itm){
													var str = '<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>';
													str += '<tr>';
													str +=	  '<td class="dtitleTd"><input type="hidden" name="didx[]" value="" /><input type="text" name="dtitle[]" value="'+((itm && itm.title)?itm.title:'')+'" class="dtitleInput" /></td>';
													str +=	  '<td width="60%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"></textarea></td>';

													if(itm && itm.desc){
														 str +=	  '<td width="90" class="dbtnTd" rowspan="2"><button class="gosiDef">[����������ǥ��]</button><br /><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
														 str += '<tr><td colspan="2" class="daccTd"><span class="font_orange">* '+itm.desc+'</span></td></tr>';
													}else{
														 str +=	  '<td class="dbtnTd"><button class="gosiDef">[����������ǥ��]</button><br /><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
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
														$j('.ditemTextarea').text('����������ǥ��');
													}else if(el){
														var $tel = $j(el).parent().parent().find('.ditemTextarea');
														$j($tel).text('����������ǥ��');
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
											<div id="ingosiDefTxtDiv" style="text-align:right;display:<?=(count($detialItems)>0)?'':'none'?>;"><input type="checkbox" name="gosiAllDef" onclick="javascript:gosiDesStrset(true);" value="" />��� ��ǰ���� "������ ����ǥ��" ����</div>
											<table width="98%" border="0" cellpadding="0" cellspacing="0" id="detailTable" style="margin:0px 10px 0px 15px; display:<?=(count($detialItems)>0)?'':'none'?>; border-bottom:1px solid #dddddd">
												<? if(count($detialItems)>0){
															foreach($detialItems as $ditem){ ?>
												<tr>
													<td class="dtitleTd"><input type="hidden" name="didx[]" value="<?=$ditem['didx']?>" /><input type="text" name="dtitle[]" value="<?=$ditem['dtitle']?>" class="dtitleInput" /></td>
													<td width="65%" class="td_con1"><textarea name="dcontent[]" class="ditemTextarea"><?=$ditem['dcontent']?></textarea></td>
													<td width="90" class="dbtnTd"><button class="gosiDef">[����������ǥ��]</button><br /><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td>
												</tr>
												<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>
												<?	 } // end foreach
												} // end if
										?>
											</table>




										</td>
									</tr>
								</table>
							</TD>
						</tr>
						   <TR>
								<TD colspan="4" background="images/table_con_line.gif"></TD>
						   </TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� ���� ����</TD>
							<TD class="td_con1" colspan="3" style="padding:5px;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width="180"></col>
							<col width=""></col>
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
									<col width="20"></col>
									<col width=""></col>
									<?for($i=0; $i<$userspec_cnt; $i++) {?>
									<tr>
										<td style="padding:5px;padding-bottom:0px;padding-left:7px;padding-right:2px;" align="center"><?=str_pad(($i+1), 2, "0", STR_PAD_LEFT);?>.</td>
										<td style="padding:5px;padding-bottom:0px;padding-left:0px;"><input name=specname[] value="<?=htmlspecialchars($specname[$i])?>" size=30 maxlength=30 class="input" style="width:100%;"></td>
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
									<td style="padding:5px;padding-bottom:0px;"><input name=specvalue[] value="<?=htmlspecialchars($specvalue[$i])?>" size=50 class="input" style="width:100%;"></td>
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
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
			<?
						if( 0 ) {
							if($_data->vender<=0) {
								$sql = "SELECT num, package_name, package_type FROM tblproductpackage ";
								$sql.= "ORDER BY num DESC ";
								$result=mysql_query($sql,get_db_conn());
			?>
						<tr>
							<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��Ű�� �׷� ����</td>
							<td class="td_con1" colspan="3">
							<table cellpadding="0" cellspacing="0" width="100%">
							<TR id="packagealertidx"<?=($_data->assembleuse=="Y"?"":" style='display:none;'")?>>
								<TD bgcolor="#FF7100" style="padding:2px;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
									<td align="center" style="padding:10px;" bgcolor="#FFF7F0" height="40"><span class="font_orange"><b>**** �ڵ�/���� �ǸŰ��� ���� ��Ű�� �׷켱���� ���Ұ� ****</b></span></td>
								</tr>
								</table>
								</TD>
							</TR>
			<?
				if($_data->assembleuse!="Y") {
			?>
							<TR id="packageselectidx"<?=($_data->assembleuse=="Y"?" style='display:none;'":"")?>>
								<TD bgcolor="#FF7100" style="padding:2px;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
									<td align="center" style="padding:10px;" bgcolor="#FFF7F0"><select name="package_num" class="input" style="width:70%;"<?=($_data->assembleuse=="Y"?" disabled":"")?>>
									<option value=""> ---------------- ��Ű�� �׷��� ������ �ּ���. ----------------- </option>
			<?
									while($row=mysql_fetch_object($result)) {
										echo "<option value=\"".$row->num."\"".($row->num==(int)$_data->package_num?" selected":"").">�ʼ�(".($row->package_type=="Y"?"Y":"N").") : ".$row->package_name."</option>\n";
									}
									mysql_free_result($result);
			?>
									</select> <A HREF="javascript:parent.location='product_package.php';"><B><img src="images/btn_package.gif" border="0" hspace="2" align=absmiddle></A></td>
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
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
			<?
							}
						}
			?>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻���</TD>
							<TD class="td_con1" colspan="3"><input name=keyword value="<? if ($_data) echo $_data->keyword; ?>" size=80 maxlength=100 onKeyDown="chkFieldMaxLen(100)" class="input" style=width:100%></TD>
						</tr>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<? if($gongtype=="N"){?>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">Ư�̻���</TD>
							<TD class="td_con1" colspan="3"><input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=43 maxlength=200 onKeyDown="chkFieldMaxLen(200)" class="input">&nbsp;<span class="font_orange">* ��ǰ�� Ư�̻����� �Է��� �ּ���.</span></TD>
						</TR>
						<? } else { ?>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">���� �Ǹż��� ǥ��</TD>
							<TD class="td_con1" colspan="3"><input name=addcode value="<? if ($_data) echo ereg_replace("\"","&quot;",$_data->addcode); ?>" size=35 maxlength=200 class="input">&nbsp;<span class="font_orange">(��: �����Ǹ� : 50��, �Ǹż��� : 100��)</span></TD>
						</TR>
						<? } ?>
						<?if(strlen($_data->productcode)==18){?>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�±� ����</TD>
							<TD class="td_con1" colspan="3">
							<DIV id="ProductTagList" name="ProductTagList" style="padding:5px;width:600px;height:68px;word-spacing:7px;background:#fafafa">
								�±׸� �ҷ����� �ֽ��ϴ�.
							</DIV>
							</TD>
						</tr>

						<script>loadProductTagList('<?=$_data->productcode?>');</script>
						<?}?>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="td_con_orange" colspan="4" style="border-top-width:1pt; border-top-color:rgb(255,153,51); border-top-style:solid;"><b><span class="font_orange">��ǰ�̹������</span></b><br><font color="black">��ǰ ���� �̹��� ����� <B>[��ǰ���� �ΰ���� =&gt; ��ǰ �����̹��� ���]</B> ���� �Ͻ� �� �ֽ��ϴ�.</font>
							<br>
							<input type=checkbox id="idx_use_imgurl" name=use_imgurl value="Y" <?=($use_imgurl=="Y"?"checked":"")?> onclick="change_filetype(this)"> <label style='cursor:hand;' onmouseover="style.textDecoration=''" onmouseout="style.textDecoration='none'" for=idx_use_imgurl><span class="font_orange"><B>��ǰ�̹��� ÷�� ����� URL�� �Է��մϴ�.</B> (�� : http://www.abc.com/images/abcd.gif)</span></label>
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�� �̹���</TD>
							<TD class="td_con1" colspan="3">
							<input type=file name="userfile" onchange="document.getElementById('size_checker').src=this.value;" style="WIDTH: 400px" class="input">
							<input type=text name="userfile_url" value="<?=$userfile_url?>" style="WIDTH: 400px; display:none" class="input">
							<span class="font_orange">(�����̹��� : 550X550)</span>
							<br><input type=checkbox id="idx_imgcheck1" name=imgcheck value="Y"<?if (strlen($_data->minimage)>0 || strlen($row->tinyimage)>0) echo "onclick=PrdtAutoImgMsg()"?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_imgcheck1><font color=#003399>���ε�� �� �̹����� ��, �� �̹��� �ڵ�����(��, �� ���� ������� ����)</font></label>
							<input type=hidden name="vimage" value="<?=$_data->maximage?>">
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
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�� �̹���</TD>
							<TD class="td_con1" colspan="3">
							<input type=file name="userfile2" style="WIDTH: 400px" onchange="document.getElementById('size_checker2').src = this.value;" class="input">
							<input type=text name="userfile2_url" value="<?=$userfile2_url?>" style="WIDTH: 400px; display:none" class="input">
							<span class="font_orange">(�����̹��� : 330X330)</span>
							<input type=hidden name="vimage2" value="<?=$_data->minimage?>">
			<?
						if ($_data) {
							if (strlen($_data->minimage)>0 && file_exists($imagepath.$_data->minimage)==true){
								echo "<br><img src='".$imagepath.$_data->minimage."' height=80 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$_data->minimage."'>";
								echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('2')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
							} else {
								echo "<br><img src=images/space01.gif>";
							}
						}
			?>
							</TD>
						</TR>
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">�� �̹���<br>&nbsp;(�������� ���� �̹���)</TD>
							<TD class="td_con1" colspan="3" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;">
							<input type=file name="userfile3" style="WIDTH: 400px" onchange="document.getElementById('size_checker3').src = this.value;" class="input">
							<input type=text name="userfile3_url" value="<?=$userfile3_url?>" style="WIDTH: 400px; display:none" class="input">
							<span class="font_orange">(�����̹��� : 200X200)</span>
							<input type=hidden name=setcolor value="<?=$setcolor?>">
							<input type=hidden name="vimage3" value="<?=$_data->tinyimage?>">
			<?
						if ($_data) {
							if (strlen($_data->tinyimage)>0 && file_exists($imagepath.$_data->tinyimage)==true){
								echo "<br><img src='".$imagepath.$_data->tinyimage."' height=70 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$_data->tinyimage."'>";
								echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('3')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
							} else {
								echo "<br><img src=images/space01.gif>";
							}
						}
			?>
							</TD>
						</TR>
						<!-- wide �̹��� -->
						<TR>
							<TD colspan="4" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">����ϼ� �̹���</TD>
							<TD class="td_con1" colspan="3" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;">
							<input type="file" name="wideimage" style="WIDTH: 400px" class="input">
							<input type="hidden" name="attechwide" value="<?=$_data->wideimage?>">
							<span class="font_orange">(�����̹��� : 400X240)</span>

							<?if(is_file($savewideimage.$_data->wideimage)){?>
							<br/>
								<img src="<?=$savewideimage.$_data->wideimage?>?t=<?=time()?>" width="150"/>
								<a href="JavaScript:DeletePrdtImg('4')"><img src="images/icon_del1.gif" align="bottom" border="0"></a>
							<?}?>
							<br/>
							* ����ϼ� ���� ���÷��� Ÿ�� �� ����ƮŸ�� ���� �̹����� ÷���ϴ� ����Դϴ�.
							<br/>
							* �ش� �̹����� ÷������ ���� ���¿��� ����ϼ� ���� ���÷��� Ÿ���� ����Ʈ�� ���� �� ��� ��ǰ �̹����� ���� ���� �ʽ��ϴ�.
							<br/>
							* ����Ʈ�̹��� ������ ���Ͻ� ��� �� ÷�θ� �Ͻ� �� ���� �Ͻø� ���� �˴ϴ�.
							</TD>
						</TR>
						<!-- wide �̹��� -->
						<TR>
							<TD class="table_cell" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">�̹��� �׵θ�</TD>
							<TD class="td_con1" colspan="3" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;">
								<input type=checkbox name=imgborder value="Y" <?=(($imgborder)=="Y"?"checked":"")?>>�ű� ��ǰ��Ͻ� �ܰ� �׵θ��� ���� &nbsp; <font class=font_orange>�׵θ� ����</font> <span id="ColorPreview" style="width:15px;font-size:12pt;background: #<?=$setcolor?>;"></span> &nbsp;<a href="javascript:SelectColor();"><img src="images/btn_color.gif" width="111" height="16" border="0" align=absmiddle></a>
							</TD>
						</TR>

						<script>change_filetype(document.form1.use_imgurl);</script>

						<tr>
							<TD class="td_con_orange" colspan="4">
							<table cellpadding="0" cellspacing="0" width="100%">
							<col width=160></col>
							<col width=></col>
							<col width=140></col>
							<tr>
								<td><B><span class="font_orange">��ǰ �󼼳��� �Է�</span></B></td>
								<td><? if($predit_type=="Y" && false){?>
											<input type=radio id="idx_checkedit1" name=checkedit checked onClick="JavaScript:htmlsetmode('wysiwyg',this)"><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_checkedit1>��������� �Է��ϱ�(����)</label> &nbsp;&nbsp; <input type=radio id="idx_checkedit2" name=checkedit onClick="JavaScript:htmlsetmode('textedit',this);"><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_checkedit2>���� HTML�� �Է��ϱ�</label>
									<? } ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox id="idx_localsave" name=localsave value="Y" <?=($localsave=="Y"?"checked":"")?> onClick="alert('��ǰ �󼼳����� ��ũ�� Ÿ���� �̹����� �� ���θ��� ���� �� ��ũ�� �����ϴ� ����Դϴ�.')"> <label style='cursor:hand;' onMouseOver="style.textDecoration='none'" onMouseOut="style.textDecoration='none'" for=idx_localsave><span class="font_orange"><B>Ÿ���� �̹��� ���θ��� ����</B></span></label></td>
							</tr>
							</table>
							</TD>
						</tr>
						<tr>
							<TD colspan="4">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td><textarea wrap=off style="WIDTH: 100%; HEIGHT: 300px" name="content" lang="ej-editor1"><?=htmlspecialchars($_data->content)?></textarea></td>
							</tr>
							</table>
							</TD>
						</tr>
						<tr>
							<td colspan="4"><img id="size_checker" style="display:none;"><img id="size_checker2" style="display:none;"><img id="size_checker3" style="display:none;"></td>
						</tr>
						<TR>
							<TD colspan=4 background="images/table_top_line.gif"></TD>
						</TR>
						</TABLE>
						</td>
					</tr>

					<?
						if( false ) {
					?>
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td colspan="2"><input type=checkbox id="idx_insertdate20" name=insertdate2 value="Y" onclick="DateFixAll(this)" <?=($insertdate_cook=="Y")?"checked":"";?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate20><span class="font_orange">��ǰ��ϳ�¥ ����</span></label></td>
						</tr>
						<tr>
							<td align="center" width="100%">
							<? if (strlen($prcode)==0) { ?>
									<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align=absmiddle width="144" height="38" border="0" vspace="5"></a>
							<? } else {?>
									<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align=absmiddle width="162" height="38" border="0" vspace="5"></B></a>
									&nbsp;
									<a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align=absmiddle width="113" height="38" border="0" vspace="5"></B></a>
							<? }?>
										</td>
										<td align="right">
							<? if (strlen($prcode)>0) { ?>
									<a href="JavaScript:NewPrdtInsert()"  onMouseOver="window.status='�ű��Է�';return true;"><img src="images/product_newregicn.gif" align=absmiddle border="0" width="142" height="38" vspace="5"></a>
							<? } ?>
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
				?>
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/design_eachjoin_stitle2.gif"  ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td height=3></td>
					</tr>
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="productItemArea">
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR id="assemblealertidx"<?=($_data->assembleuse=="Y"?"":" style='display:none;'")?>>
							<TD colspan="2" bgcolor="#FF7100" style="padding:2px;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<td align="center" style="padding:10px;" bgcolor="#FFF7F0"><span class="font_orange"><b>**** �ڵ�/���� �ǸŰ��� ���� ��ǰ�ɼ��� ���Ұ� ****</b></span></td>
							</tr>
							</table>
							</TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼ� Ÿ�� ����</TD>
							<TD class="td_con1">
							<input type=radio id="idx_searchtype0" name=searchtype style="border:none" onclick="ViewLayer('layer0')" value="0" <?if($searchtype=="0") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype0>�ɼ����� ����</label>
							<img width=10 height=0>
							<input type=radio id="idx_searchtype1" name=searchtype style="border:none" onclick="ViewLayer('layer1');alert('�ɼ�1�� �ɼ�2�� �ִ� 10����\n�� �ɼǺ� ���������� �����ϰ� �˴ϴ�.\n������ ������ ���̻��� �ɼǵ��� �����˴ϴ�.');" value="1" <?if($searchtype=="1") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>��ǰ �ɼ� + <font color=#FF0000>������</font></label> <a href="JavaScript:optionhelp()"><img src="images/product_optionhelp3.gif" align=absmiddle border=0></a>
							<img width=10 height=0>
							<input type=radio id="idx_searchtype2" name=searchtype style="border:none" onclick="ViewLayer('layer2')" value="2" <?if($searchtype=="2") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>��ǰ �ɼ� ������ ���</label>
							<?if($gongtype=="N" && (int)$_data->vender==0){?>
							<img width=10 height=0>
							<input type=radio id="idx_searchtype3" name=searchtype style="border:none" onclick="ViewLayer('layer3')" value="3" <?if($searchtype=="3") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype3>�ɼǱ׷�</label>
							<?}?>
							</font>
							</td>
						</tr>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<tr><td height="6"></td></tr>
						</table>
						<div id=layer0 style="margin-left:0;display:hide; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						</TABLE>
						</div>
						<div id=layer1 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
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
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ�ɼ� �Ӽ���</TD>
							<TD class="td_con1"><b>�ɼ�1 �Ӽ���</b><B> :<FONT color=#ff6000> </B></FONT><input name=option1_name value="<? if (strlen($_data->option1)>0) echo htmlspecialchars($optionarray1[0]); ?>" size=20 maxlength=20 class="input">&nbsp;&nbsp;&nbsp;&nbsp;<b>�ɼ�2 �Ӽ���</b><B> :<FONT color=#128c02> </B></FONT><input name=option2_name value="<? if (strlen($_data->option2)>0) echo htmlspecialchars($optionarray2[0]); ?>" size=20 maxlength=20 class="input"></TD>
						</tr>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD colspan="2" style="padding-top:3pt; padding-bottom:3pt;">
							<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
							<TR>
								<TD><IMG SRC="images/distribute_01.gif"></TD>
								<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
								<TD><IMG SRC="images/distribute_03.gif"></TD>
							</TR>
							<TR>
								<TD background="images/distribute_04.gif"></TD>
								<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
								<TD width="100%" class="notice_blue">
								1) �ɼǰ��� �Է½� �ǸŰ����� ���õǰ� �ɼǰ������� ���Ű� ����˴ϴ�.<br>
								2) �ǸŻ�ǰ ǰ���� ��� �ɼ� �������� ���� �ִ��� ��ǰ���Ŵ� ������� �ʽ��ϴ�.<br>
								&nbsp;<b>&nbsp;&nbsp;</b>�ɼ� ���������θ� ��ǰ ������ �� ��� �ǸŻ�ǰ �������� ���������� ������ �ּ���.<br>
								3) �ɼ� ������ ���Է½� �ɼ� �������� ������ ���°� �Ǹ� "0" �Է½� �ɼ� �������� ǰ�� ���°� �˴ϴ�.</TD>
								<TD background="images/distribute_07.gif"></TD>
							</TR>
							<TR>
								<TD><IMG SRC="images/distribute_08.gif"></TD>
								<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
								<TD><IMG SRC="images/distribute_10.gif"></TD>
							</TR>
							</TABLE>
							</TD>
						</TR>
						<TR>
							<TD colspan="2">
							<TABLE cellSpacing=0 cellPadding=0 width="754px" bgColor=#ffffff border=0>
							<TR>
								<TD width="80px" bgColor="#F9F9F9">
								<TABLE cellSpacing=0 cellPadding=0 border=0>
								<TR bgColor=#FF7100 height=2>
									<TD noWrap width=2></TD>
									<TD noWrap width=2></TD>
									<TD width="100%"></TD>
									<TD noWrap width=2></TD>
									<TD noWrap width=2></TD>
								</TR>
								<TR height=50>
									<TD bgColor=#FF7100 rowSpan=25></TD>
									<TD rowSpan=25></TD>
									<TD align=middle><B>�ɼ�1 �Ӽ�</B></TD>
									<TD rowSpan=25></TD>
									<TD bgColor=#FF7100 rowSpan=25></TD>
								</TR>
								<TR bgColor=#dadada height=1>
									<TD></TD>
								</TR>
								<TR height=1>
									<TD></TD>
								</TR>
			<?
							for($i=1;$i<=10;$i++){
								if($i==6) echo "<tr height=5><td></td></tr>";
								echo "<tr height=7><td></td></tr>";
								echo "<tr height=19><TD align=middle><input type=text name=optname1 value=\"".trim(htmlspecialchars($optionarray1[$i]))."\" size=8 class=\"input\"></td></tr>";
							}
							echo "<tr height=2><td></td></tr>";
							echo "<tr height=2><td colspan=5 bgcolor=#FF7100></td></tr>";
			?>
								</TABLE>
								</TD>
								<TD width="80px" bgColor="#F9F9F9">
								<TABLE cellSpacing=0 cellPadding=0 border=0>
								<TR bgColor=#0071C3 height=2>
									<TD noWrap width=2></TD>
									<TD noWrap width=2></TD>
									<TD width="100%"></TD>
									<TD noWrap width=2></TD>
									<TD noWrap width=2></TD>
								</TR>
								<TR height=50>
									<TD bgColor=#0071C3 rowSpan=25></TD>
									<TD rowSpan=25></TD>
									<TD align=middle><B>����</B></TD>
									<TD rowSpan=25></TD>
									<TD bgColor=#0071C3 rowSpan=25></TD>
								</TR>
								<TR bgColor=#dadada height=1>
									<TD></TD>
								</TR>
								<TR height=1>
									<TD></TD>
								</TR>
			<?
							for($i=0;$i<10;$i++){
								if($i==5) echo "<tr height=5><td></td></tr>";
								echo "<tr height=7><td></td></tr>";
								echo "<tr height=21><td align=center><input type=text name=optprice size=8 ";
								echo " value=\"".$option_price[$i]."\" ";
								echo "onkeyup=\"strnumkeyup(this)\" class=\"input\"></td></tr>";
							}
							echo "<tr height=2><td></td></tr>";
							echo "<tr height=2><td colspan=5 bgcolor=#0071C3></td></tr>";
			?>
								</TABLE>
								</TD>
								<TD vAlign=top width="585px" bgColor=#ffffff>
								<TABLE cellSpacing=0 cellPadding=0 border=0>
								<TR bgColor=#57B54A height=2>
									<TD width=2 rowSpan=4></TD>
									<TD width=2></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=80></TD>
									<TD width=2></TD>
									<TD width=2 rowSpan=4></TD>
								</TR>
								<TR bgColor=#f1ffef height=27>
									<TD width=2 rowspan="2"></TD>
									<TD align=middle colSpan=10 bgcolor="#F9F9F9"><b>�ɼ�2 �Ӽ�</b></TD>
									<TD width=2 rowspan="2"></TD>
								</TR>
								<TR bgColor=#f1ffef height=19>
			<?
								for($i=1;$i<=10;$i++){
									echo "<TD align=middle width=\"20%\" bgcolor=\"#F9F9F9\"><input type=text name=optname2 value=\"".htmlspecialchars($optionarray2[$i])."\" size=8 class=\"input\"></td>";
								}
			?>
								</TR>
								<TR bgColor=#F9F9F9 height=4>
									<TD colSpan=12></TD>
								</TR>
								<TR bgColor=#57B54A height=2>
									<TD colSpan=14></TD>
								</TR>
								<TR height=6>
									<TD colSpan=2 rowSpan="22"></TD>
									<TD colSpan=10></TD>
									<TD colSpan=2 rowSpan="22"></TD>
								</TR>
			<?
							for($i=0;$i<10;$i++){
								if($i!=0 && $i!=5) echo "<tr><td colspan=10 height=7></td></tr>";
								else if($i==5) echo "<tr><td colspan=10 height=6></td></tr>
													<tr><td colspan=10 height=1 bgcolor=#DADADA></td></tr>
													<tr><td colspan=10 height=5></td></tr>";
								echo "<tr height=19>";
								for($j=0;$j<10;$j++){
									echo "<TD align=middle><input type=text name=optnumvalue[".$j."][".$i."] value=\"".$option_quantity_array[$j*10+$i+1]."\" size=8 maxlength=3 onkeyup=\"strnumkeyup(this)\" class=\"input\"></TD>\n";
								}
								echo "</tr>";
							}
			?>
								</TABLE>
								</TD>
							</TR>
							</TABLE>
							</TD>
						</TR>
						<tr><td colspan=2 height=5></td></tr>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						</table>
						</div>

						<div id=layer2 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼ�1</TD>
							<TD class="td_con1">
			<?
							$option1="";
							$optname1="";
							if ($_data) {
								if (strlen($_data->option1)>0) {
									$tok = strtok($_data->option1,",");
									$optname1=$tok;
									$tok = strtok("");
									$option1=$tok;
								}
							}
			?>
							<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
							<col width=76></col>
							<col width=></col>
							<TR>
								<TD>1)�Ӽ���</TD>
								<TD style="PADDING-LEFT: 5px"><input name=toptname1 value="<? if ($_data && strlen($_data->option1)>0) echo $optname1; ?>" size=50 maxlength=20 class="input"></TD>
							</TR>
							<TR>
								<TD>2)�Ӽ�</TD>
								<TD style="PADDING-LEFT: 5px"><input name=toption1 value="<? if ($_data && strlen($_data->option1)>0) echo htmlspecialchars($option1); ?>" size=50 maxlength=230 class="input"></TD>
							</TR>
							<TR>
								<TD style="PADDING-LEFT: 3px" colSpan=2>* �ɼ��� �Ӽ������� ���� �Ǵ� ������ �Ǵ� �뷮 ���� �Է��ؼ� ����ϼ���.<br>* �Ӽ��� �Ӽ��� ���� ���γ����� �Է��մϴ�.<br>&nbsp;&nbsp;&nbsp;��)����,�Ķ�,��� �Ǵ� 95,100,105 �� ���� �ĸ�(,)�� �����Ͽ� ������� �Է��մϴ�.</TD>
							</TR>
							</TABLE>
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼ�1 ����</TD>
							<TD class="td_con1">
							<?if($gongtype=="N"){?>
								<input name=toption_price value="<? if ($_data) echo $_data->option_price; ?>" size=50 maxlength=250 class="input">&nbsp;<span class="font_orange"><b>��) 1000,2000,3000</b></span><br>
								* �ɼ�1 ���� �Է½� �ǸŰ����� ���õ˴ϴ�.<br>
								* �ɼ�1 ���� �Է½� �ǸŰ��� ��� ù��° ������ �ǸŰ������� ���˴ϴ�.<br>
								* ī�װ��� ��ǰ ��½� "�ǸŰ��� (�⺻��)"�� ǥ�� �˴ϴ�.<br>
								* �޼��� ������ <?=($popup=="YES"?"":"<A HREF=\"javascript:parent.parent.topframe.GoMenu(1,'shop_mainproduct.php');\">")?><span class="font_blue">�������� > ���θ� ȯ�漳�� > ��ǰ ���� ��Ÿ ����</span></A> ���� ���� ����.
							<? } else { ?>
								���� ������ ���������� ��� �ɼ�1 ������ �������� �ʽ��ϴ�.<input type=hidden name=toption_price>
							<? } ?>
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼ�2</TD>
							<TD class="td_con1">
			<?
						$option2="";
						$optname2="";
						if ($_data) {
							if (strlen($_data->option2)>0) {
								$tok = strtok($_data->option2,",");
								$optname2=$tok;
								$tok = strtok("");
								$option2=$tok;
							}
						}
			?>
							<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
							<col width=76></col>
							<col width=></col>
							<TR>
								<TD>1)�Ӽ���</TD>
								<TD style="PADDING-LEFT: 5px"><input name=toptname2 value="<? if ($_data && strlen($_data->option2)>0) echo $optname2; ?>" size=50 maxlength=20 class="input"></TD>
							</TR>
							<TR>
								<TD>2)�Ӽ�</TD>
								<TD style="PADDING-LEFT: 5px"><input name=toption2 value="<? if ($_data && strlen($_data->option2)>0) echo htmlspecialchars($option2); ?>" size=50 maxlength=230 class="input"></TD>
							</TR>
							<TR>
								<TD style="PADDING-LEFT: 3px" colSpan=2>* �ɼ�1 ��� ����� ������ "<B>�ɼ�1 ����</B>"���� �����մϴ�.</TD>
							</TR>
							</TABLE>
							</TD>
						</tr>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						</table>
						</div>
						<div id=layer3 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<?if($gongtype=="N"){?>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼǱ׷� ����</TD>
							<TD class="td_con1">
							<select name=optiongroup style="width: 70%" class="select">
			<?
						$sqlopt = "SELECT option_code,description FROM tblproductoption ";
						$resultopt = mysql_query($sqlopt,get_db_conn());
						$optcnt=0;
						while($rowopt = mysql_fetch_object($resultopt)){
							if($optcnt++==0) echo "<option value=0>�ɼǱ׷��� �����ϼ���.";
							echo "<option value=\"".$rowopt->option_code."\"";
							if($optcode==$rowopt->option_code) echo " selected";
							echo ">".$rowopt->description."</option>";
						}
						mysql_free_result($resultopt);
						if($optcnt==0) echo "<option value=0>����Ͻ� �ɼǱ׷��� �����ϴ�.</option>";
			?>
							</select>
							<?if($popup!="YES"){?><A HREF="javascript:parent.location='product_option.php';"><B><img src="images/btn_option.gif" width="105" height="18" border="0" hspace="2" align=absmiddle></B></A><?}?>
							<?if($optcnt==0) echo "<script>document.form1.optiongroup.disabled=true;</script>";?>

							<br>* (��ǰ����+�ɼ�) ���氡�� ���� �ɼǱ׷��� �̿��� �ּ���.
							<br>* �ɼǱ׷� ���� �ɼ�1�� �ɼ�2�� �ڵ� �����˴ϴ�.
							<br>* �ɼǱ׷� ���ý� �ش� �ɼǱ׷쿡 ��ϵ� ��ǰ�ɼ��� Ȯ���� �� �ֽ��ϴ�.
							</TD>
						</TR>
						</TABLE>
						<?}?>
						</div>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan="2"background="images/table_con_line.gif"></TD>
						</TR>





						<tr>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0"><B>������ �ٹ̱�</B></TD>
							<TD class="td_con1">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<?
						$iconarray = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28");
						$totaliconnum = 0;
						for($i=0;$i<count($iconarray);$i++) {
							if($i%7==0) echo "<TR height=25>";
							echo "<TD width=\"14%\"><input type=checkbox name=icon onclick=CheckChoiceIcon('".$totaliconnum."') value=\"".$iconarray[$i]."\" ";
							if($iconvalue2[$iconarray[$i]]=="Y") echo "checked";
							echo "><img src=\"".$Dir."images/common/icon".$iconarray[$i].".gif\" border=0 align=absmiddle></td>\n";
							if($i%7==6) echo "</tr>";
							$totaliconnum++;
						}
			?>
							<TR>
								<TD colSpan=7 height=5></TD>
							</TR>
							<TR>
								<TD colSpan=7>
								<table cellpadding="1" cellspacing="1" width="100%" bgcolor="#FF9933">
								<tr>
									<td width="585" bgcolor="#FFFCF6">
									<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td width="167" align=center style="padding-top:5pt; padding-bottom:5pt;"><b><span class="font_orange">�� ������</span></b></td>
										<td width="424" style="padding-top:5pt; padding-bottom:5pt;">
										<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<?
									$iconpath=$Dir.DataDir."shopimages/etc/";
									$usericon = array("U1","U2","U3","U4","U5","U6");
									$cnt=0;
									for($i=0;$i<count($usericon);$i++){
										if(file_exists($iconpath."icon".$usericon[$i].".gif")){
											$cnt++;
											if($cnt%3==1) echo "<TR height=25>";
											echo "<td width=33%><input type=checkbox name=icon onclick=CheckChoiceIcon('".$totaliconnum."') value=\"".$usericon[$i]."\" ";
											if($iconvalue2[$usericon[$i]]=="Y") echo "checked";
											echo "> <img src=\"".$iconpath."icon".$usericon[$i].".gif\" border=0 align=absmiddle></td>\n";
											if($cnt%3==0) echo "</tr>";
											$totaliconnum++;
										}
									}
									if($cnt==0) {
										echo "<tr><td align=center><font color=red>��ϵ� �� �������� �����ϴ�.</font></td></tr>";
									} else {
										for($i=$cnt;$i<6;$i++){
											echo "<td width=33%>&nbsp;</td>";
											if($i%3==2) echo "</tr><tr>";
										}
										if($cnt<6) echo "</tr>";
									}
			?>
										</TABLE>
										</td>
									</tr>
									</table>
									</td>
								</tr>
								<tr>
									<td bgcolor="#FF9933" style="padding-left:5pt;"><font color="white"><span style="letter-spacing:-0.5;">* �� ��ǰ�� 3������ �������� ����� �� �ֽ��ϴ�.<br>* <b>������ ����� 6�� ���� ���</b> �����մϴ�.</span></font></td>
								</tr>
								<tr>
									<td bgcolor="#FF9933"><A href="JavaScript:IconMy()"><IMG src="images/productregister_iconinsert.gif" align=absMiddle border=0 width="120" height="20"></A>&nbsp;<A href="JavaScript:IconList()"><IMG src="images/productregister_icondown.gif" align=absMiddle border=0 width="98" height="20"></A></td>
								</tr>
								</table>
								</TD>
							</TR>
							</TABLE>
							</TD>
						</tr>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ŷ� ��ü ����</TD>
							<TD class="td_con1"><select name=bisinesscode class="select">
							<option value="0"> -------- �ŷ���ü�� �����ϼ���. -------- </option>
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
						if($bizcnt==0) echo "<option value=\"0\">����Ͻ� �ŷ���ü�� �����ϴ�.</option>";
			?>
							</select><br>
							<span class="font_orange">* �ŷ� ��ü ����� <a href="javascript:parent.parent.topframe.GoMenu(4,'product_business.php');"><span class="font_blue">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �ŷ�ó ����</span></a>���� ����ϼ���.</span>
							</TD>
						</TR>
						<input type=hidden name=old_display value="<?=$_data->display?>">
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ��Ÿ����</TD>
							<TD class="td_con1">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<? /*
							<TR>
								<TD><input type=checkbox id="idx_bankonly1" name=bankonly value="Y" <? if ($_data) { if ($bankonly=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankonly1>���ݰ����� ����ϱ�</label> <span class="font_orange">(���� ��ǰ�� �Բ� ���Ž� ������ ���ݰ����θ� ����˴ϴ�.)</span></TD>
								<td></td>
							</TR>
							*/ ?>
							<? if ($card_splittype=="O") { ?>
							<tr>
								<td><input type=checkbox id="idx_setquota1" name=setquota value="Y" <? if ($_data) { if ($setquota=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_setquota1>�����δ� ������</label> <span class="font_orange">(�����ݾ�/�������Һΰ����� <a  href="shop_payment.php"><b>�������ñ�ɼ���</b></a>�� ����)</span></td>
								<td></td>
							</tr>
							<? } ?>
							<? /*
							-- ���Ű� �ϴ����� �̵� 2013.10.07
							if ($gongtype=="N") { ?>
							<TR>
								<TD style="PADDING-TOP: 5px"><input type=checkbox id="idx_dicker1" name=dicker value="Y" <? if ($_data) { if ($dicker=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dicker1><b>�ǸŰ��� ��ü����</b></label> &nbsp;<input type=text name=dicker_text value="<?=$dicker_text?>" size=20 maxlength=20 onKeyDown="chkFieldMaxLen(20)" class="input"> <span class="font_orange">* ��) �ǸŴ���ǰ, ��㹮��(000-000-000)</span></TD>
								<td></td>
							</TR>
							<TR>
								<TD colSpan=2><!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b>�� ��ǰ �ǸŰ��� ��� ���ϴ� ������ ��½�Ű�� ����Դϴ�.<br> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> �Է°��� ���� ���� �ѱ� 10��, ���� 20�ڷ� ���ѵǾ� �ֽ��ϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> ���� �ֹ��� ������� �ʽ��ϴ�.</TD>
							</TR>
							<? } */ ?>

							<TR>
								<TD style="PADDING-TOP: 5px"><input type=checkbox id="idx_deliinfono1" name=deliinfono value="Y" <? if ($_data) { if ($deliinfono=="Y") echo "checked";}?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliinfono1>���/��ȯ/ȯ������ �������</label> <font color=#AA0000>(��ǰ��ȭ�� �ϴܿ� ���/��ȯ/ȯ�������� ����ȵ�)</font></TD>
								<td></td>
							</TR>

							</TABLE>
							</TD>
						</TR>
			<?
			if($sns_ok == "Y"){
			?>
						<TR>
							<TD colspan=2 background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">SNS ��뿩��</TD>
							<TD class="td_con1"><input type=radio id="sns_state1" name=sns_state value="Y" <? if ($_data) { if ($_data->sns_state=="Y") echo "checked"; }  ?> onclick="ViewSnsLayer('block')" ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=sns_state1>�����</label> &nbsp; <input type=radio id="sns_state2" name=sns_state value="N" <? if ($_data) { if ($_data->sns_state !="Y") echo "checked"; } else echo "checked"; ?> onclick="ViewSnsLayer('none')" ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=sns_state2>������</label></TD>
						</TR>
			<?
				if($arSnsType[0] =="B"){
			?>
						<tr id ="sns_optionWrap" style="display:none;">
							<td colspan=2>
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<col width=160></col>
								<col></col>
								<col width=160></col>
								<col></col>
								<TR><TD colspan=4 background="images/table_con_line.gif"></TD></TR>
								<tr>
								<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��õ�� ������(��)</TD>
								<TD class="td_con1"><input name=sns_reserve1 value="<?=$_data->sns_reserve1?>" size=10 maxlength=6 class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve1_type.value);"> <select name="sns_reserve1_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N"<?=($_data->sns_reserve1_type!="Y"?" selected":"")?>>������(��)</option><option value="Y"<?=($_data->sns_reserve1_type!="Y"?"":" selected")?>>������(%)</option></select>
								</TD>
								<TD class="table_cell" style="border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">����õ�� ������(��)</TD>
								<TD class="td_con1"><input name=sns_reserve2 value="<?=$_data->sns_reserve2?>" size=10 maxlength=6 class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve2_type.value);"> <select name="sns_reserve2_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N"<?=($_data->sns_reserve2_type!="Y"?" selected":"")?>>������(��)</option><option value="Y"<?=($_data->sns_reserve2_type!="Y"?"":" selected")?>>������(%)</option></select>
								</TD>
								</tr>
								</table>
							</TD>
						</tr>
			<?	}
			}?>
						 <TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�����ϱ� ��뿩��</TD>
							<TD class="td_con1"><input type=radio id="present_state1" name=present_state value="Y" <? if ($_data) { if ($_data->present_state=="Y") echo "checked"; } ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=present_state1>�����</label> &nbsp; <input type=radio id="present_state2" name=present_state value="N" <? if ($_data) { if ($_data->present_state!="Y") echo "checked"; } else echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=present_state2>������</label></TD>
						</TR>
						<input type=hidden  name="pester_state" value="Y"  />
						
						<? /*
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������ ��뿩��</TD>
							<TD class="td_con1"><input type=radio id="pester_state1" name=pester_state value="Y" <? if ($_data) { if ($_data->pester_state=="Y") echo "checked"; } ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=pester_state1>�����</label> &nbsp; <input type=radio id="pester_state2" name=pester_state value="N" <? if ($_data) { if ($_data->pester_state!="Y") echo "checked"; } else echo "checked";  ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=pester_state2>������</label></TD>
						</TR>
						
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						*/ ?>
			<?
			if($arRecomType[0] =="B" && $arRecomType[1] == "B"){
			?>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">ù���Ž� ��õ�� ����</TD>
							<TD class="td_con1"><input name=first_reserve value="<?=$_data->first_reserve?>" size=10 maxlength=6 class="input" style="width:20%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.first_reserve_type.value);"> <select name="first_reserve_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N"<?=($_data->first_reserve_type!="Y"?" selected":"")?>>������(��)</option><option value="Y"<?=($_data->first_reserve_type!="Y"?"":" selected")?>>������(%)</option> </select> <font color="#ff0000"> *snsȫ���� ���� ù���ſ��� ����</font></span>
							</TD>
						</TR>
			<?}?>
						<TR>
							<TD colspan="2" background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">������û��ǰ</TD>
							<TD class="td_con1"><input type=radio id="gonggu_product1" name="gonggu_product" value="Y" <? if ($_data) { if ($_data->gonggu_product=="Y") echo "checked"; } else echo "checked";  ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=gonggu_product1>������</label> &nbsp; <input type=radio id="gonggu_product2" name="gonggu_product" value="N" <? if ($_data) { if ($_data->gonggu_product=="N") echo "checked"; } ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=gonggu_product2>��������</label></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif" style="height:1px;"></TD>
						</TR>


					<? // ���� ���� ���� ���� ���� �߰�
					if(false !== $naverep = checkNaverEp()){
						if(!_empty($naverep['shopping'])){ ?>
								<TR>
									<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0"><b>���̹� ���ļ��� ����</b></TD>

									<TD class="td_con1">
									<input type="checkbox" name="syncNaverEp" value="0" <?=(($syncNaverEp=='0')?'checked':'')?> />���� �Ұ�� ���̹� ���� ���� ������ �ش� ��ǰ�� ���� �մϴ�.
									</TD>
								</TR>
								<TR>
									<TD colspan="2" background="images/table_con_line.gif"></TD>
								</TR>
					<? }
					}
					?>

						</TABLE>
						</td>
					</tr>


					<?
					#####################��ǰ�� ȸ�������� ����##########################################
					?>
					<tr>
						<td height=50></td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/design_memgroup_stitle.gif"  ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td height=3></td>
					</tr>
					<tr>
						<td>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<col width=200></col>
								<col></col>
								<TR>
									<TD colspan=2 background="images/table_top_line.gif"></TD>
								</TR>
								<tr>
									<TD class="table_cell">ȸ�����</td>
									<TD class="table_cell">������ �� ���αݾ�</td>
								</tr>



								<?
									if (strlen($prcode)>0) {
										$dSql = "SELECT discountYN,discountrates,discountprices,over_discount FROM tblmemberdiscount ";
										$dSql .= "WHERE productcode='$prcode' ";
										$dResult = mysql_query($dSql,get_db_conn());
										$dRow = mysql_fetch_object($dResult);
											$overDiscount = $dRow->over_discount;
											$discountYN =  $dRow->discountYN;
									}
								?>

								<tr>
									<TD colspan="2" background="images/table_con_line.gif"></TD>
								</tr>
								<tr>
									<TD class="table_cell">
										<img src="images/icon_point5.gif" width="8" height="11" border="0">��뿩��
									</TD>
									<TD class="td_con1">
										<input type="radio" name="discountYN" value="Y" <?=$discountYN=="Y"? "checked":"";?>>�����&nbsp;
										<input type="radio" name="discountYN" value="N" <?=($discountYN=="N" || $discountYN=="")? "checked":"";?>>������
									</td>

								</tr>

								<tr>
									<TD colspan="2" background="images/table_con_line.gif"></TD>
								</tr>
								<tr>
									<TD class="table_cell">
										<img src="images/icon_point5.gif" width="8" height="11" border="0">ȸ���׷캰 ���ΰ�<br />&nbsp;&nbsp;�ߺ����� ���ɿ��� <a href="#" onmouseover="showMemSale.style.visibility='visible'" onmouseout="showMemSale.style.visibility='hidden'">[?]</a></div>
										<div id="showMemSale">ȸ���׷캰 ���μ����� [������ �޴� > ȸ�� > ȸ����� ���� > ȸ����� ���/����/����]���� �����մϴ�.</div>
									</TD>
									<TD class="td_con1">
										<input type="radio" name="over_discount" value="N" <?=$overDiscount=="N"? "checked":"";?>>�ߺ��Ұ�(��ǰ�� ���θ� ����)&nbsp;
										<input type="radio" name="over_discount" value="Y" <?=($overDiscount=="Y" || $overDiscount=="")? "checked":"";?>>�׷캰 �ߺ����ΰ���
									</td>

								</tr>



								<tr>
									<TD colspan="2" background="images/table_con_line.gif"></TD>
								</tr>
								<?
								$mSql = "SELECT group_code,group_name FROM tblmembergroup ";
								$mResult = mysql_query($mSql,get_db_conn());
								while($mRow = mysql_fetch_object($mResult)){
								?>
								<tr>
									<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">
										<?=$mRow->group_name?>
										<input type="hidden" name="group_code[]" value="<?=$mRow->group_code?>">
									</TD>
									<TD class="td_con1">
										<?
											if (strlen($prcode)>0) {
												$dSql = "SELECT discountrates,discountprices,over_discount FROM tblmemberdiscount ";
												$dSql .= "WHERE productcode='$prcode' AND group_code='$mRow->group_code'";
												$dResult = mysql_query($dSql,get_db_conn());
												$dRow = mysql_fetch_object($dResult);
											}
										?>
										<input name="discount_rates[]" id="discount_rates<?=$mRow->group_code?>" size="10" type="text" class="input" value="<?=$dRow->discountrates?>" onkeyup="javascript:autoCal('1',this.value,'discount_prices<?=$mRow->group_code?>')" style="width:50px; text-align:right; padding-right:5px;">%
										(
										<input name="discount_prices[]" id="discount_prices<?=$mRow->group_code?>" size="20" type="text" class="input" value="<?=(int)$dRow->discountprices?>" onkeyup="javascript:autoCal('2',this.value,'discount_rates<?=$mRow->group_code?>')" style="width:70px; text-align:right; padding-right:5px;">��
										)
										����
									</TD>
								</tr>
								<tr>
									<TD colspan="2" background="images/table_con_line.gif"></TD>
								</tr>
								<?
								}
								?>

								</tr>
								<? /*
								<tr><td height="15"></td></tr>
								<tr>
									<td colspan="2" align="center" width="100%"><a href="javascript:DiscountPrd('discountprd');"><img src="images/btn_discountedit.gif" align="absmiddle" border="0"></a></td>
								</tr>
								*/ ?>
							</table>
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<?
					//}
					#####################��ǰ�� ȸ�������� �� ##########################################
					?>



					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td colspan="2"><input type=checkbox id="idx_insertdate30" name=insertdate3 value="Y" onclick="DateFixAll(this)" <?=($insertdate_cook=="Y")?"checked":"";?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_insertdate30><span class="font_orange">��ǰ��ϳ�¥ ����</span></label></td>
						</tr>
						<tr>
							<td align="center" width="100%">
							<? if (strlen($prcode)==0) { ?>
									<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align=absmiddle width="144" height="38" border="0" vspace="5"></a>
							<? } else {?>
									<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align=absmiddle width="162" height="38" border="0" vspace="5"></B></a>
									&nbsp;
									<a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align=absmiddle width="113" height="38" border="0" vspace="5"></B></a>
							<? }?>
										</td>
										<td align="right">
							<? if (strlen($prcode)>0) { ?>
									<a href="JavaScript:NewPrdtInsert()"  onMouseOver="window.status='�ű��Է�';return true;"><img src="images/product_newregicn.gif" align=absmiddle border="0" width="142" height="38" vspace="5"></a>
							<? } ?>
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
				</tr>
				<tr><td height=20 colspan=2></td></tr>
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
				</table>
		</td>
	</tr>
	</table>
	</td>
	</tr>
</table>

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



// ��ǰ�� ȸ��������
function DiscountPrd(mode) {
	document.form1.mode.value=mode;
	document.form1.submit();
}



//��ǰ�� ȸ�������� �ڵ����
function autoCal(gubun,val,obj){
	if( document.form1.sellprice.value <= 0 ) {
		alert("��ǰ������ ���� �Է��ϼž� �մϴ�.");
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
<?if ($gongtype=="Y") {?>
	gongname="���۰�";
	gongname2="������";
<?} else {?>
	 gongname="�Һ��ڰ���";
	 gongname2="�ǸŰ���";
<?}?>
	if (document.form1.productname.value.length==0) {
		alert("��ǰ���� �Է��ϼ���.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>300) {
		alert('�� �Է°����� ���̰� �ѱ� 150�ڱ����Դϴ�. �ٽ��ѹ� Ȯ���Ͻñ� �ٶ��ϴ�.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		document.form1.consumerprice.value = 0;
		//alert(gongname+"�� �Է��ϼ���.");
		//document.form1.consumerprice.focus();
		//return;
	}
	if (isNaN(document.form1.consumerprice.value)) {
		alert(gongname+"�� ���ڷθ� �Է��ϼ���.(�޸�����)");
		document.form1.consumerprice.focus();
		return;
	}
<?if($_data->vender<=0){?>
	if(document.form1.sellprice.disabled==false) {
		if (document.form1.sellprice.value.length==0) {
			alert(gongname2+"�� �Է��ϼ���.");
			document.form1.sellprice.focus();
			return;
		}
		if (isNaN(document.form1.sellprice.value)) {
			alert(gongname2+"�� ���ڷθ� �Է��ϼ���.(�޸�����)");
			document.form1.consumerprice.focus();
			return;
		}
	}
<?}?>
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

<?if ($gongtype=="N") {?>
	if (document.form1.checkquantity[2].checked==true) {
<?} else {?>
	if (document.form1.checkquantity[1].checked==true) {
<?}?>
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

	searchtype=false;
	for(i=0;i<document.form1.searchtype.length;i++) {
		if(document.form1.searchtype[i].checked==true) {
			searchtype=true;
			shop="layer"+i;
			break;
		}
	}

	if(searchtype==false) {
		alert("�ɼ� Ÿ���� �����ϼ���.\n\n�������� Ÿ���� ��ǰ�� ��� �ɼǱ׷� ����� �Ұ��Ͽ���\n�� Ȯ���Ͻñ� �ٶ��ϴ�.");
		document.form1.searchtype[0].focus();
		return;
	}

	if(document.form1.sellprice.disabled==false) {
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
<?	if($gongtype=="N" && (int)$_data->vender==0){?>
		} else if(shop=="layer3") {
			if(document.form1.optiongroup.selectedIndex==0) {
				alert("�ɼǱ׷��� �����ϼ���.");
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
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve1.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve1.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(Number(document.form1.sns_reserve1.value)>100 || Number(document.form1.sns_reserve1.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.sns_reserve1.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve1.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.sns_reserve1.focus();
				return;
			}
		}
	}
	if (document.form1.sns_reserve2.value.length>0) {
		if(document.form1.sns_reserve2_type.value=="Y") {
			if(isDigitSpecial(document.form1.sns_reserve2.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve2.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve2.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(Number(document.form1.sns_reserve2.value)>100 || Number(document.form1.sns_reserve2.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.sns_reserve2.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve2.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
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
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.first_reserve.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getPointCount(document.form1.first_reserve.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.first_reserve.focus();
				return;
			}

			if(Number(document.form1.first_reserve.value)>100 || Number(document.form1.first_reserve.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.first_reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.first_reserve.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.first_reserve.focus();
				return;
			}
		}
	}
<?
}
?>
	if(document.form1.use_imgurl.checked!=true) {
		filesize = Number(document.form1.size_checker.fileSize) + Number(document.form1.size_checker2.fileSize) + Number(document.form1.size_checker3.fileSize) ;
		if(filesize><?=$maxfilesize?>) {
			alert('�ø��÷��� �ϴ� ���Ͽ뷮�� 500K�̻��Դϴ�.\n���Ͽ뷮�� üũ�Ͻ��Ŀ� �ٽ� �̹����� �÷��ּ���');
			return;
		}
	}
	tempcontent = document.form1.content.value;
	/*
<?if ($predit_type=="Y"){ ?>
	if(mode=="modify" && tempcontent.length>0 && tempcontent.indexOf("<")==-1 && tempcontent.indexOf(">")==-1 && !confirm("�������� ����߰��� �ؽ�Ʈ�θ� �Է��Ͻ� �󼼼�����\n�ٹٲٱⰡ �����Ǿ� ���θ����� �ٸ��� ������ �� �ֽ��ϴ�.\n\n���Է��Ͻðų� ���� ���θ����� �ش� ��ǰ�� �󼼼�����\n�״�� ���콺�� �巡���Ͽ� �ٿ��ֱ⸦ �ؼ� ���Է��ϼž� �մϴ�.\n\n���� ���� �������� �ʰ� �����Ͻ÷��� [Ȯ��]�� ��������.")){
		return;
	}
<?}?>*/
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}
	if (document.form1.insertdate1.checked==true) document.form1.insertdate.value="Y";
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

<? /* ������ ���� jdy	*/?>
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
		a_val = "��û �����Ḧ ���� �Ͻðڽ��ϱ�?";
	}else{
		a_val = "��û �����Ḧ �ź� �Ͻðڽ��ϱ�?";
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

	if(confirm("�����Ḧ ���� �Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="comm_admin";
		document.form1.submit();
	}

}



<? /* ������ ���� jdy	*/?>

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

//################################�߰� ī�װ����� ###############
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
</body>
</html>
