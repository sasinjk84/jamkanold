<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/ext/product_func.php");

if(substr($_venderdata->grant_product,1,1)!="Y") {
	echo "<html></head><body onload=\"alert('��ǰ���� ���� ������ �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
}
@set_time_limit(300);


setlocale(LC_CTYPE, 'ko_KR.eucKR');

###################################### ������� ������ üũ #######################################
$usevender=setUseVender();

unset($venderlist);
if($usevender==true) {
	$sql = "SELECT vender,id,com_name FROM tblvenderinfo WHERE disabled=0 AND delflag='N' ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$venderlist[$row->vender]=$row;
	}
	mysql_free_result($result);
}
#####################################################################################################

function CutStringY($str, $start, $end)
{
	$result = substr($str, $start, $end); // �ϴ� ���ڿ��� �ڸ��ϴ�.
	preg_match('/^([\x00-\x7e]|.{2})*/', $result, $string);	// �ڿ� ���� ?�� �����ݴϴ�..
	return $string[0];
}

$imagepath=$Dir.DataDir."shopimages/product/";
$filename="prdtexcelupfile.csv";
@unlink($imagepath.$filename);

if(strlen($setcolor)==0) $setcolor="000000";
$rcolor=HexDec(substr($setcolor,0,2));
$gcolor=HexDec(substr($setcolor,2,2));
$bcolor=HexDec(substr($setcolor,4,2));
$quality = "90";

$maxsize=130;
$makesize=130;

$maxsize=$makesize+10;
if(strpos(" ".$_shopdata->etctype,"IMGSERO=Y")) {
	$imgsero="Y";
}

$mode=$_POST["mode"];
$vender=(int)$_POST["vender"];
$code=$_POST["code"];
$upfile=$_FILES["upfile"];

$date1=date("Ym");		// ��ϼ������� ���� ���� �ʿ� ����
$date=date("dHis");		// ��ϼ������� ���� ���� �ʿ� ����

if($mode=="upload" && strlen($code)==12 && strlen($upfile[name])>0 && $upfile[size]>0) {
	########################### TEST ���θ� Ȯ�� ##########################
	//DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	//������ü Ȯ��
	if($vender>0 && strlen($venderlist[$vender]->vender)<=0) {
		$vender=0;
	}

	//�з� Ȯ��
	$sql = "SELECT type FROM tblproductcode ";
	$sql.= "WHERE codeA='".substr($code,0,3)."' AND codeB='".substr($code,3,3)."' ";
	$sql.= "AND codeC='".substr($code,6,3)."' AND codeD='".substr($code,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if(substr($row->type,-1)!="X") {
			echo "<html><head></head><body onload=\"alert('��ǰ�� ����� �з� ������ �߸��Ǿ����ϴ�.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
		}
	} else {
		echo "<html><head></head><body onload=\"alert('��ǰ�� ����� �з� ������ �߸��Ǿ����ϴ�.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
	}
	mysql_free_result($result);

	$ext = strtolower(substr($upfile[name],strlen($upfile[name])-3,3));
	if($ext=="csv") {
		/*
		$tempfile=@file($upfile[tmp_name]);
		if(count($tempfile)>101) {
			echo "<html><head></head><body onload=\"alert('1ȸ ��� ������ ��ǰ���� 100�� ���� �Դϴ�.\\n\\n100�� �̻��� ��� ������ ����Ͻñ� �ٶ��ϴ�.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
		}
		*/

		copy($upfile[tmp_name],$imagepath.$filename);
		chmod($imagepath.$filename,0664);
	} else {
		echo "<html><head></head><body onload=\"alert('���������� �߸��Ǿ� ���ε尡 �����Ͽ����ϴ�.\\n\\n��� ������ ������ �ؽ�Ʈ(TXT) ���ϸ� ��� �����մϴ�.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
	}

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
			echo "<html><head></head><body onload=\"alert('��ǰ�ڵ带 �����ϴµ� �����߽��ϴ�. ����� �ٽ� �õ��ϼ���.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
		}
		mysql_free_result($result);
	} else {
		$productcode = "000001";
	}

	$sql = "SELECT bridx, brandname FROM tblproductbrand ";
	$result = mysql_query($sql,get_db_conn());
	while ($rows = mysql_fetch_object($result)) {
		$bridx[$rows->brandname] = $rows->bridx;
	}
	mysql_free_result($result);

	$i=0;
	$filepath=$imagepath.$filename;
	$fp=fopen($filepath,"r");
	$yy=0;

	while($field=fgetcsv($fp, 10000)) {
		if($yy++==0) continue;
		if(strlen($field[0])==0) {
			continue;
		}

		$field[1]=(int)$field[1];
		$field[2]=(int)$field[2];
		$field[3]=(int)$field[3];

		$reservetype="N";
		$field_exp="";
		if(strlen($field[10])>0) {
			$field_type = substr($field[10],-1);
			if($field_type!="%") {
				$field[10]=(int)$field[10];
				if($field[10]<0 || $field[10]>999999) {
					$field[10]=0;
				}
			} else {
				$reservetype="Y";
				$field[10]=substr($field[10],0,-1)*1;
				$field_exp = explode(".", $field[10]);

				if($field[10]<0 || $field[10]>100 || strlen($field[10])>5) {
					$field[10]=0;
				} else if(count($field_exp)>1 && strlen($field_exp[1])>2) {
					$field[10]=$field_exp[0].".".substr($field_exp[1],0,2);
				}
			}
		} else {
			$field[10]=0;
		}

		if(strlen($field[11])==0) $field[11]="NULL";
		if($field[15]!="Y") $field[15]="N";
		if($field[19]!="Y") $field[19]="N";

		if($i++>0) {
			$productcode = ((int)$productcode)+1;
			$productcode = sprintf("%06d",$productcode);
		}

		//�ǸŰ� üũ
		if($field[2]<=0) {
			//�ǸŰ��� �Է��ϼ���.
		}

		//�ɼ��׸� üũ
		if(strlen($field[13])>0 && strlen($field[12])==0) {
			//���û���1 ������ �Է��ϸ� �ݵ�� ���û���1���� ������ �Է��ؾ� �մϴ�.
			$field[13]="";
		}

		if(strlen($field[12])==0) {
			$field[14]="";
		}

		if(strlen($field[12])>0 && strlen($field[13])>0) {
			$tempopt1=explode("|",$field[12]);
			$tempopt1_price=explode("|",$field[13]);
			if((count($tempopt1)-1)!=(count($tempopt1_price))) {
				$field[12]="";
				$field[13]="";
				$field[14]="";
			}
		} else if(strlen($field[12])>0) {
			$tempopt1=explode("|",$field[12]);
			if(count($tempopt1)<=1) {
				$field[12]="";
				$field[13]="";
				$field[14]="";
			}
		}
		if(strlen($field[14])>0) {
			$tempopt2=explode("|",$field[14]);
			if(count($tempopt2)<=1) {
				$field[14]="";
			}
		}
		$field[12]=str_replace("|",",",$field[12]);
		$field[12]=str_replace(" ,",",",$field[12]);
		$field[12]=str_replace(", ",",",$field[12]);
		$field[13]=str_replace("|",",",$field[13]);
		$field[13]=str_replace(" ,",",",$field[13]);
		$field[13]=str_replace(", ",",",$field[13]);
		$field[14]=str_replace("|",",",$field[14]);
		$field[14]=str_replace(" ,",",",$field[14]);
		$field[14]=str_replace(", ",",",$field[14]);

		$image_name = $code.$productcode;

		$maximage="";
		$minimage="";
		$tinyimage="";

		$maximage_url=$field[16];
		$minimage_url=$field[17];
		$tinyimage_url=$field[18];

		if(strlen($maximage_url)>0) {
			$maximage_url=eregi_replace("http://","",$maximage_url);
			$temp=explode("/",$maximage_url);
			$host=$temp[0];
			$path=str_replace(" ","%20", eregi_replace($host,"",$maximage_url));

			$ext=strtolower(substr(strrchr($maximage_url,"."),1));
			if($ext=="gif" || $ext=="jpg") {
				$maximage=$image_name.".".$ext;
				$fdata=getRemoteImageData($host,$path,$ext);

				if(strlen($fdata)>0) {
					$filepath=$imagepath.$maximage;
					$fp2=fopen($filepath,"w");
					fputs($fp2,$fdata);
					fclose($fp2);
					chmod($filepath,0664);
					$tempsize=@getimagesize($filepath);
					if($tempsize[0]>0 && $tempsize[1]>0 && (preg_match("/^(1|2)$/",$tempsize[2]))) {
						if($field[19]=="Y") {
							$minimage=$image_name."2.".$ext;
							$tinyimage=$image_name."3.".$ext;
							copy($imagepath.$maximage, $imagepath.$minimage);
							chmod($imagepath.$minimage,0664);
							copy($imagepath.$maximage, $imagepath.$tinyimage);
							chmod($imagepath.$tinyimage,0664);
						}
					} else {
						@unlink($filepath);
						$maximage="";
					}
				} else {
					$maximage="";
				}
			}
		}

		if($field[19]!="Y") {
			if(strlen($minimage_url)>0) {
				$minimage_url=eregi_replace("http://","",$minimage_url);
				$temp=explode("/",$minimage_url);
				$host=$temp[0];
				$path=str_replace(" ","%20", eregi_replace($host,"",$minimage_url));

				$ext=strtolower(substr(strrchr($minimage_url,"."),1));
				if($ext=="gif" || $ext=="jpg") {
					$minimage=$image_name."2.".$ext;
					$fdata=getRemoteImageData($host,$path,$ext);

					if(strlen($fdata)>0) {
						$filepath=$imagepath.$minimage;
						$fp2=fopen($filepath,"w");
						fputs($fp2,$fdata);
						fclose($fp2);
						chmod($filepath,0664);
						$tempsize=@getimagesize($filepath);
						if($tempsize[0]==0 || $tempsize[1]==0 || (!preg_match("/^(1|2)$/",$tempsize[2]))) {
							@unlink($filepath);
							$minimage="";
						}
					} else {
						$minimage="";
					}
				}
			}

			if(strlen($tinyimage_url)>0) {
				$tinyimage_url=eregi_replace("http://","",$tinyimage_url);
				$temp=explode("/",$tinyimage_url);
				$host=$temp[0];
				$path=str_replace(" ","%20", eregi_replace($host,"",$tinyimage_url));

				$ext=strtolower(substr(strrchr($tinyimage_url,"."),1));
				if($ext=="gif" || $ext=="jpg") {
					$tinyimage=$image_name."3.".$ext;
					$fdata=getRemoteImageData($host,$path,$ext);

					if(strlen($fdata)>0) {
						$filepath=$imagepath.$tinyimage;
						$fp2=fopen($filepath,"w");
						fputs($fp2,$fdata);
						fclose($fp2);
						chmod($filepath,0664);
						$tempsize=@getimagesize($filepath);
						if($tempsize[0]==0 || $tempsize[1]==0 || (!preg_match("/^(1|2)$/",$tempsize[2]))) {
							@unlink($filepath);
							$tinyimage="";
						}
					} else {
						$tinyimage="";
					}
				}
			}
		}


		if($field[19]=="Y" && strlen($minimage)>0) {
			$imgname=$imagepath.$minimage;
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

		if(strlen($tinyimage)>0) {
			$imgname=$imagepath.$tinyimage;
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize2=250;
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
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imagePNG($im2,$imgname);
				}

				ImageDestroy($im);
				ImageDestroy($im2);
			}
		}


		############################### ��ǰ �󼼼��� �̹��� ��� ���� #############################
		$etcimagepath=$Dir.DataDir."design/etc/";
		if(is_dir($etcimagepath)==false) {
			mkdir($etcimagepath);
			chmod($etcimagepath,0755);
		}

		$body=$field[20];
		$arrimgurl=array();
		$p=explode("\n", $body);
		for($u=0;$u<count($p);$u++) {
			while(eregi("[^=\"']*\.(gif|jpg|bmp|png)([\"|\'| |>]){1}", $p[$u], $val)){
				$arrimgurl[substr($val[0],0,-1)]=substr($val[0],0,-1);
				$p[$u]=str_replace(substr($val[0],0,-1),"",$p[$u]);
			}
		}

		while(list($key,$val)=each($arrimgurl)) {
			$file_url=urldecode($val);
			if(substr($file_url,0,7)=="http://") {
				$file_url=eregi_replace("http://","",$file_url);
				$temp=explode("/",$file_url);
				$host=$temp[0];
				$path=str_replace(" ","%20", eregi_replace($host,"",$file_url));

				$filename=substr(strrchr($file_url,"/"),1);
				$x=0;
				while(file_exists($etcimagepath.$filename)){
					$file_ext=substr( strrchr($filename,"."),1);
					$file_name=substr($filename, 0, strlen($filename) - strlen(strrchr($filename,".")));
					$file_name=substr($file_name, 0, strlen($file_name) - strlen(strrchr($file_name,"[")));

					$filename=$file_name."[".$x."].".$file_ext;
					$x++ ;
				}

				$ext=substr(strrchr($filename,"."),1);

				$fdata=getRemoteImageData($host,$path,$ext);

				if(strlen($fdata)>0) {
					$filepath=$etcimagepath.$filename;
					$fp2=fopen($filepath,"w");
					fputs($fp2,$fdata);
					fclose($fp2);
					chmod($filepath,0604);

					$size=getimagesize($filepath);

					if($size[0]>0 && $size[1]>0 && (preg_match("/^(1|2|3|6)$/",$size[2]))) {
						$body=str_replace($val,"/".RootPath.DataDir."design/etc/".$filename,$body);
					} else {
						@unlink($filepath);
					}
				}
			}
		}


		$productdisprice = (isset($field[21]) && intval($field[21]))?intval($field[21]):'';

		// ����� ���� ���� ó��
		$userspecarr = array();
		for($jj=22;$jj<31;$jj++){
			if(strlen(trim($field[$jj++])) >0 && strlen(trim($field[$jj])) > 0) array_push($userspecarr,$field[$jj-1]."".$field[$jj]);
		}
		$userspec = (count($userspecarr) > 0)?implode("=",$userspecarr):'';

		// �ǸŰ��� ��ü ����
		$etctype = '';
		if(strlen(trim($field[32]))>0) $etctype .= "DICKER=".$field[32]."";

		// ���� ����
		$etcapply_coupon = (isset($field[33]) && $field[33]=='Y')?'Y':'N';
		$etcapply_reserve = (isset($field[34]) && $field[34]=='Y')?'Y':'N';
		$etcapply_gift = (isset($field[35]) && $field[35]=='Y')?'Y':'N';
		$etcapply_return = (isset($field[36]) && $field[36]=='Y')?'Y':'N';


		###################################################################################################
		# 0=>��ǰ��, 1=>���߰���, 2=>�ǸŰ�, 3=>���Ű�, 4=>������, 5=>������, 6=>�귣��, 7=>�𵨸�, 8=>�����, 9=>�����ڵ�, 10=>������(��), 11=>���
		# 12=>���û���1, 13=>���û���1����, 14=>���û���2, 15=>��������
		# 16=>ū�̹���, 17=>�����̹���, 18=>�����̹���, 19=>�߼��̹����ڵ�����, 20=>��ǰ�󼼼���
		# 21=>���Ű�,22=>����ڽ��Ѹ�1,23=>����ڽ���1����,24=>����ڽ��Ѹ�2,25>����ڽ���2����,26=>����ڽ��Ѹ�3,27=>����ڽ���3����,28=>����ڽ��Ѹ�4,29=>����ڽ���4����,30=>����ڽ��Ѹ�5,31=>����ڽ���5����,32->�ǸŰ��ݴ�ü����
		# 33=>������������Ұ�,34=>����������Ұ�,35=>���Ż���ǰ����Ұ�,36>��Ȯ��ȯ�ҺҰ�,37=>���1����,38=>���2����
		###################################################################################################

		//$curdate = date("YmdHis");
		$date=$date+1;
		if (strlen($date)==7) $date="0".$date;
		else if (strlen($date)==6) $date="00".$date;

		$curdate=$date1.$date;

		$field[0] = CutStringY($field[0], 0, 100);
		$field[4] = CutStringY($field[4], 0, 20);
		$field[5] = CutStringY($field[5], 0, 30);
		$field[6] = CutStringY($field[6], 0, 50);
		$field[7] = CutStringY($field[7], 0, 50);



		if(strlen($field[6])>0) {
			if($bridx[$field[6]]==0) {
				$sql = "INSERT tblproductbrand SET ";
				$sql.= "brandname = '".$field[6]."' ";
				if($brandinsert = @mysql_query($sql,get_db_conn())) {
					$bridx[$field[6]] = @mysql_insert_id(get_db_conn());
				}
			}
		}

		$sql = "INSERT tblproduct SET ";
		$sql.= "productcode		= '".$code.$productcode."', ";
		$sql.= "productname		= '".str_replace("'","\'",$field[0])."', ";
		$sql.= "sellprice		= ".$field[2].", ";
		$sql.= "consumerprice	= ".$field[1].", ";
		$sql.= "buyprice		= ".$field[3].", ";
		$sql.= "reserve			= '".$field[10]."', ";
		$sql.= "reservetype		= '".$reservetype."', ";
		$sql.= "production		= '".str_replace("'","\'",$field[4])."', ";
		$sql.= "madein			= '".str_replace("'","\'",$field[5])."', ";
		$sql.= "brand			= '".$bridx[$field[6]]."', ";
		$sql.= "model			= '".str_replace("'","\'",$field[7])."', ";
		$sql.= "opendate		= '".$field[8]."', ";
		$sql.= "selfcode		= '".$field[9]."', ";
		$sql.= "quantity		= ".$field[11].", ";
		$sql.= "keyword			= '', ";
		$sql.= "addcode			= '', ";
		$sql.= "maximage		= '".$maximage."', ";
		$sql.= "minimage		= '".$minimage."', ";
		$sql.= "tinyimage		= '".$tinyimage."', ";
		$sql.= "option_price	= '".$field[13]."', ";
		$sql.= "option_quantity	= '', ";
		$sql.= "option1			= '".$field[12]."', ";
		$sql.= "option2			= '".$field[14]."', ";
		//$sql.= "etctype			= '', ";
		$sql.= "etctype			= '".$etctype."', ";
		$sql.= "userspec		= '".$userspec."', ";
		$sql.= "deli_price		= '0', ";
		$sql.= "deli			= 'N', ";
		$sql.= "display			= '".$field[15]."', ";
		$sql.= "date			= '".$curdate."', ";
		if($vender>0) {
			$sql.= "vender		= '".$vender."', ";
		}
		$sql.= "regdate			= now(), ";
		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".str_replace("'","\'",$body)."' ";
		// �߰� �ʵ� ó�� �κ�
		if(!_empty($productdisprice)) $sql .= ",productdisprice='".$productdisprice."'";
		$sql .= ",etcapply_coupon='".$etcapply_coupon."'";
		$sql .= ",etcapply_reserve='".$etcapply_reserve."'";
		$sql .= ",etcapply_gift='".$etcapply_gift."'";
		$sql .= ",etcapply_return='".$etcapply_return."'";

		@mysql_query($sql,get_db_conn());
		$pridx = mysql_insert_id(get_db_conn());

		$sql = "insert into tblcategorycode set productcode='".$code.$productcode."',categorycode='".$code."'";
		@mysql_query($sql,get_db_conn());

		// ����׸� �߰� �κ�
		if(_isInt($pridx) && count($field) > 37){
			$getItem = array();
			for($kk=37;$kk < count($field);$kk++){
				$item = array('dtitle'=>$field[$kk++],'dcontent'=>$field[$kk]);
				if(!_empty($item['dtitle'])) array_push($getItem,$item);
			}

			if(_array($getItem)){
				_editProductDetails($pridx,$getItem);
			}
		}
	}
	@fclose($fp);

	if($vender>0 && $i>0) {
		$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
		$sql.= "WHERE vender='".$vender."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$prdt_allcnt=(int)$row->prdt_allcnt;
		$prdt_cnt=(int)$row->prdt_cnt;
		mysql_free_result($result);

		setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $vender);
		setVenderDesignInsert($vender, $code);
	}

	echo "<html><head></head><body onload=\"alert('��ǰ ����� �Ϸ�Ǿ����ϴ�.');location.href='".$_SERVER["PHP_SELF"]."';\"></body></html>";exit;
}
?>
<? INCLUDE "header.php"; ?>
<style>
	iframe, table, tbody,tfoot,thead,a,img{border:0px; padding:0px; margin:0px;}
	.tb_wrap{border-bottom:2px solid #999}
	.tb_wrap td{padding:3px 0px; border-bottom:1px solid #999}
	.tb_wrap .td_last{border:0px;}
	.tb_contents{table-layout:fixed;border:0px;}
	.tb_contents td{border:0px;}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function ACodeSendIt(f,obj) {
	if(obj.ctype=="X") {
		f.code.value = obj.value+"000000000";
	} else {
		f.code.value = obj.value;
	}

	burl = "/admin/product_excelupload.ctgr.php?depth=2&code=" + obj.value;
	curl = "/admin/product_excelupload.ctgr.php?depth=3";
	durl = "/admin/product_excelupload.ctgr.php?depth=4";
	BCodeCtgr.location.href = burl;
	CCodeCtgr.location.href = curl;
	DCodeCtgr.location.href = durl;
}

var isupload=false;
function CheckForm() {
	if(isupload==true) {
		alert("######### ���� ��ǰ���� ������Դϴ�. #########");
		return;
	}
	if(document.form1.code.value.length!=12) {
		codelen=document.form1.code.value.length;
		if(codelen==0) {
			alert("��ǰ�� ����� ��з��� �����ϼ���.");
			document.form1.code1.focus();
		} else if(codelen==3) {
			alert("��ǰ�� ����� �ߺз��� �����ϼ���.");
			BCodeCtgr.form1.code.focus();
		} else if(codelen==6) {
			alert("��ǰ�� ����� �Һз��� �����ϼ���.");
			CCodeCtgr.form1.code.focus();
		} else if(codelen==9) {
			alert("��ǰ�� ����� ���κз��� �����ϼ���.");
			DCodeCtgr.form1.code.focus();
		} else {
			alert("��ǰ�� ����� ī�װ��� �����ϼ���.");
			DCodeCtgr.form1.code.focus();
		}
		return;
	}

	isupload=true;
	document.all.uploadButton.style.filter = "Alpha(Opacity=60) Gray";
	document.form1.mode.value="upload";
	document.form1.submit();
}
</script>
<script type="text/javascript" src="lib.js.php"></script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
	<col width=190></col>
	<col width=20></col>
	<col width=></col>
	<col width=20></col>
	<tr>
		<td width=190 valign=top nowrap background="images/minishop_leftbg.gif">
		<? include ("menu.php"); ?>
		</td>
		<td width=20 nowrap></td>
		<td valign=top style="padding-top:20px">
			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
							<tr>
								<td>
									<table border=0 cellpadding=0 cellspacing=0 width=100% >
										<tr>
											<td><img src="images/product_vender_allupdate_title.gif" alt="��ǰ �ϰ� ���ε�"></td>
										</tr>
										<tr>
											<td height=5 background="images/minishop_titlebg.gif">
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height=10></td></tr>
							<tr>
								<td>
									<table border=0 cellpadding=0 cellspacing=0 width=100% >
										<tr>
											<td colspan=3 >
												<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
													<tr>
														<td  bgcolor="#F5F5F9" style="padding:20px">
															<table border=0 cellpadding=0 cellspacing=0 width=100%>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ټ� ��ǰ������ ����(csv)���Ϸ� ����� �ϰ� ������ϴ� ����Դϴ�.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���(csv)�� �ٿ�ε� ������ �� ������ ���� �����Ͽ� ����մϴ�.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">����(csv)�� ������ ��� ���� �޴��� �������� �ʰ� �޴� ���� ��� ��ǰ�� �����Ͽ� ����մϴ�.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">����� ���ϴ� ī�װ� ���� �� ������ ����(csv)������ ����ϸ� ��ǰ�� ��� �˴ϴ�.</td>
																</tr>
																<tr>
																	<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">����ϴ� ��ǰ ���� ���� ����Ͽ� �ð��� �ҿ�ǹǷ� �Ϸ� �ɶ����� ��ٸ��ñ� �ٶ��ϴ�.</td>
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
							<!-- ó���� ���� ��ġ ���� -->
							<tr><td height=40></td></tr>
							<tr>
								<td>
									<form name="form1" action="<?=$_SERVER[PHP_SELF]?>" method="post" enctype="multipart/form-data">
										<input type="hidden" name="mode">
										<input type="hidden" name="code" value="">
										<table cellSpacing=0 cellPadding=0 width="100%" border="0" class="tb_wrap">
											<tr>
												<td background="images/table_top_line.gif" colspan=2></td>
											</tr>
											<tr>
												<td class="table_cell" width="139"><img src="/admin/images/icon_point2.gif" width="8" height="11" border="0">���� ��Ͼ�� �ٿ�ε�</td>
												<td class="td_con1" ><A HREF="/admin/images/sample/product.csv"><img src="/admin/images/btn_down1.gif" border=0 align=absmiddle></A> <span class="font_orange">������(CSV)������ �������� �� ������ ���� �ۼ��մϴ�.</span></td>
											</tr>
											<tr>
												<td colspan="2" background="images/table_con_line.gif"></td>
											</tr>
											<tr>
												<td class="table_cell" width="139"><img src="/admin/images/icon_point2.gif" width="8" height="11" border="0">��ǰ ī�װ� ����</td>
												<td class="td_con1" >
													<table border=0 cellpadding=0 cellspacing=0 width="100%" class="tb_contents">
														<col width=145></col>
														<col width=3></col>
														<col width=145></col>
														<col width=3></col>
														<col width=145></col>
														<col width=3></col>
														<col width=></col>
														<tr>
															<td>
																<select name="code1" style=width:145 onchange="ACodeSendIt(document.form1,this.options[this.selectedIndex])">
																<option value="">---- �� �� �� ----</option>
										<?
																$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
																$sql.= "WHERE codeB='000' AND codeC='000' ";
																$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
																$result=mysql_query($sql,get_db_conn());
																while($row=mysql_fetch_object($result)) {
																	$ctype=substr($row->type,-1);
																	if($ctype!="X") $ctype="";
																	echo "<option value=\"".$row->codeA."\" ctype='".$ctype."'>".$row->code_name."";
																	if($ctype=="X") echo " (���Ϻз�)";
																	echo "</option>\n";
																}
																mysql_free_result($result);
										?>
																</select>
															</td>
															<td></td>
															<td>
																<iframe name="BCodeCtgr" src="/admin/product_excelupload.ctgr.php?depth=2" width="145" height="21" scrolling=no frameborder=no></iframe>
															</td>
															<td></td>
															<td>	<iframe name="CCodeCtgr" src="/admin/product_excelupload.ctgr.php?depth=3" width="145" height="21" scrolling=no frameborder=no></iframe></td>
															<td></td>
															<td><iframe name="DCodeCtgr" src="/admin/product_excelupload.ctgr.php?depth=4" width="145" height="21" scrolling=no frameborder=no></iframe></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2" background="images/table_con_line.gif"></td>
											</tr>
											<tr>
												<td class="table_cell td_last" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������(CSV) ���</td>
												<td class="td_con1 td_last" ><input type="file" name="upfile" style="width:54%" class="input"> <span class="font_orange">������(CSV) ���ϸ� ��� �����մϴ�.</span></td>
											</tr>
											<tr>
												<td background="images/table_top_line.gif" colspan=2></td>
											</tr>
										</table>
										<input type="hidden" name="vender" value="<?=$_VenderInfo->vidx?>" />
									</form>
								</td>
							</tr>
							<tr><td height=15></td></tr>
							<tr>
								<td align="center">
									<img src="images/btn_fileup.gif" id="uploadButton" width="113" height="38" border="0" style="cursor:hand" onclick="CheckForm(document.form1);">
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
<?=$onload?>
<? INCLUDE "copyright.php"; ?>