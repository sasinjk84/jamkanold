<?
setlocale(LC_CTYPE, 'ko_KR.eucKR');
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");


include_once($Dir."lib/ext/product_func.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-4";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

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
$goodsType=$_POST["goodsType"];

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
		# 33=>������������Ұ�,34=>����������Ұ�,35=>���Ż���ǰ����Ұ�,36>��Ȯ��ȯ�ҺҰ�,

#######################################################################################################
#������
		# 37=>����Ÿ��(day,time,checkout,period,long), 38=>�⺻�뿩��, 39=>�����ļ�����(mv,re)
		# 40=>�������뿩��(0,1:�����), 41=>�ּҽð� 1~24, 42=>�⺻���, 43=>1�ð��ʰ��ð�����
		# 44=>���� 12�ð� �뿩���(Y,N), 45=>���� 12�ð� �뿩����� ��� 12�ð� ���: 24�ð������ �ۼ�Ʈ
		# 46=>1���ʰ��� ���ݱ���(day,time), 47=>1���ʰ��� ���ݱ����� �ð��� ��� ���� 12�ð� ���: 24�ð� ����� �ۼ�Ʈ
		# 48=>üũ�νð�, 49=>üũ�ƿ��ð�
		
		# 50=>����:0 ����:1, 51=>�ɼǸ�, 52=>�ɼǵ��, 53=>�ɼ����󰡰�, 54=>�ɼ�������, 55=>���ΰ���, 56=>�ɼ����
		# 57=>�г�/�Ͻú�, 58=>������, 59=>������

		# 60=>�����Ⱑ��, 61=>�������ָ�����, 62=>�ؼ����Ⱑ��, 63=>�ؼ������ָ�����, 64=>�ָ�����
		# 65=>���Ͽ��డ��:Y, ���Ͽ���Ұ���:N, 66=>��ǰŸ��(��ǰ:product,���:location)
		# 67=>�ߵ������� �ؾ���, 68=>����ī������
		# 69=>���1����,70=>���2����
#########################################################################################################
		
		# 37=>����Ÿ��(day,time,checkout,period,long), 38=>�⺻�뿩��, 39=>�����ļ�����(mv,re)
		# 40=>�������뿩��(0,1:�����), 41=>�ּҽð� 1~24
		# 42=>���� 12�ð� �뿩���(Y,N)
		# 43=>1���ʰ��� ���ݱ���(day,half,time)
		# 44=>üũ�νð�, 45=>üũ�ƿ��ð�
		
		# 46=>����:0 ����:1, 47=>�ɼǸ�, 48=>�ɼǵ��, 49=>�ɼ����󰡰�, 50=>�ɼ�������, 51=>���ΰ���, 52=>�ɼ����
		# 53=>1�ð��ʰ��ð������ۼ�Ʈ, 54=>1�ð��ʰ��ð�����
		# 55=>���� 12�ð� �뿩����� ��� 12�ð� ���: 24�ð������ �ۼ�Ʈ,56=>���� 12�ð� �뿩����� ��� 12�ð� ���
		# 57=>1���ʰ��� ���ݱ����� 12�ð��� ��� ���� 12�ð� ���: 24�ð� ����� �ۼ�Ʈ,58=>1���ʰ��� ���ݱ����� 12�ð��� ��� ���� 12�ð� ���
		# 59=>1���ʰ��� ���ݱ����� 1�ð��� ��� ���� 12�ð� ���: 24�ð� ����� �ۼ�Ʈ,60=>1���ʰ��� ���ݱ����� 1�ð��� ��� ���� 12�ð� ���
		# 61=>�г�/�Ͻú�, 62=>������, 63=>������

		# 64=>�����Ⱑ��, 65=>�������ָ�����, 66=>�ؼ����Ⱑ��, 67=>�ؼ������ָ�����, 68=>�ָ�����
		# 69=>���Ͽ��డ��:Y, ���Ͽ���Ұ���:N, 70=>��ǰŸ��(��ǰ:product,���:location)
		# 71=>�ߵ������� �ؾ���, 72=>����ī������
		# 73=>���1����,74=>���2����
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
		}if(!_empty($vender)){
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
		$sql.= "rental = '".$goodsType."', ";
		$sql.= "regdate			= now(), ";
		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".str_replace("'","\'",$body)."' ";
		// �߰� �ʵ� ó�� �κ�
		if(!_empty($productdisprice)) $sql .= ",productdisprice='".$productdisprice."'";
		$sql .= ",etcapply_coupon='".$etcapply_coupon."'";
		$sql .= ",etcapply_reserve='".$etcapply_reserve."'";
		$sql .= ",etcapply_gift='".$etcapply_gift."'";
		$sql .= ",etcapply_return='".$etcapply_return."'";

		$sql.= ",today_reserve	= '".$field[69]."' ";

		@mysql_query($sql,get_db_conn());
		$pridx = mysql_insert_id(get_db_conn());

		$sql = "insert into tblcategorycode set productcode='".$code.$productcode."',categorycode='".$code."'";
		@mysql_query($sql,get_db_conn());

		// ����׸� �߰� �κ�
		if(_isInt($pridx) && count($field) > 73){
			$getItem = array();
			for($kk=73;$kk < count($field);$kk++){
				$item = array('dtitle'=>$field[$kk++],'dcontent'=>$field[$kk]);
				if(!_empty($item['dtitle'])) array_push($getItem,$item);
			}

			if(_array($getItem)){
				_editProductDetails($pridx,$getItem);
			}
		}

		if($goodsType=="2"){
			// ��Ż �ɼ� ó��
			$productoptions = array();
			$roptname=explode("|",$field[47]);
			$roptgrade=explode("|",$field[48]);
			$roptcustprice=explode("|",$field[49]);
			$roptpricedisc=explode("|",$field[50]);
			$roptnomalprice=explode("|",$field[51]);
			$roptprtcount=explode("|",$field[52]);
			
			//�ʰ��ð�����
			$roptTimeover_percent = explode("|",$field[53]);
			$roptTimeover_price = explode("|",$field[54]);
			$roptHalfday_percent = explode("|",$field[55]);
			$roptHalfday_price = explode("|",$field[56]);
			$roptOverHalfTime_percent = explode("|",$field[57]);
			$roptOverHalfTime_price = explode("|",$field[58]);
			$roptOverOneTime_percent = explode("|",$field[59]);
			$roptOverOneTime_price = explode("|",$field[60]);

			$roptoptionpay=explode("|",$field[61]);
			$roptdeposit=explode("|",$field[62]);
			$roptprepay=explode("|",$field[63]);

			$roptbusyss=explode("|",$field[64]);
			$roptbusyHoliss=explode("|",$field[65]);
			$roptsemiBusyss=explode("|",$field[66]);
			$roptsemiBusyHoliss=explode("|",$field[67]);
			$roptholiss=explode("|",$field[68]);
			for($oi = 0;$oi < sizeof($roptname);$oi++){
				$tmpopt = array();
				$tmpopt['idx'] =_isInt($_REQUEST['roptidx'][$oi])?$_REQUEST['roptidx'][$oi]:'';
				$tmpopt['grade'] = $roptgrade[$oi];
				$tmpopt['optionName'] = ($field[46] == '0')?'���ϰ���':$roptname[$oi];
				$tmpopt['custPrice'] = _isInt($roptcustprice[$oi])?$roptcustprice[$oi]:0;
				$tmpopt['priceDiscP'] = _isInt($roptpricedisc[$oi])?$roptpricedisc[$oi]:0;
				$tmpopt['nomalPrice'] = $roptnomalprice[$oi];
				$tmpopt['productCount'] = $roptprtcount[$oi];

				//�ʰ��ð�����
				$tmpopt['productTimeover_percent'] = $roptTimeover_percent[$oi];
				$tmpopt['productTimeover_price'] = $roptTimeover_price[$oi];
				$tmpopt['productHalfday_percent'] = $roptHalfday_percent[$oi];
				$tmpopt['productHalfday_price'] = $roptHalfday_price[$oi];
				$tmpopt['productOverHalfTime_percent'] = $roptOverHalfTime_percent[$oi];
				$tmpopt['productOverHalfTime_price'] = $roptOverHalfTime_price[$oi];
				$tmpopt['productOverOneTime_percent'] = $roptOverOneTime_percent[$oi];
				$tmpopt['productOverOneTime_price'] = $roptOverOneTime_price[$oi];

				
				$tmpopt['optionPay'] = $roptoptionpay[$oi];
				$tmpopt['deposit'] = $roptdeposit[$oi];
				$tmpopt['prepay'] = $roptprepay[$oi];
				
				$optquantity = $optquantity + $tmpopt['productCount'];
				
				$tmpopt['busySeason'] = _isInt($roptbusyss[$oi])?$roptbusyss[$oi]:0;
				$tmpopt['busyHolidaySeason'] = _isInt($roptbusyHoliss[$oi])?$roptbusyHoliss[$oi]:0;
				$tmpopt['semiBusySeason'] = _isInt($roptsemiBusyss[$oi])?$roptsemiBusyss[$oi]:0;
				$tmpopt['semiBusyHolidaySeason'] = _isInt($roptsemiBusyHoliss[$oi])?$roptsemiBusyHoliss[$oi]:0;
				$tmpopt['holidaySeason'] = _isInt($roptholiss[$oi])?$roptholiss[$oi]:0;
				
				array_push($productoptions,$tmpopt);
				if($field[46] == '0') break;
			}
			
			// ���� ���� ����
			if($goodsType == '2' && _array($productoptions)){
				$checkquantity = 'C';
				$sellprice = $productoptions[0]['nomalPrice'];
				$consumerprice = $productoptions[0]['custPrice'];
				$discountRate = $productoptions[0]['priceDiscP'];
				$quantity = $optquantity;
			}
		

			// �뿩 ��ǰ ����
			$commi = rentCommitionByCategory($code,$vender);
			$rentProductValue = array();
			$rentProductValue['pridx'] = $pridx;
			$rentProductValue['istrust'] = '1';
			$rentProductValue['location'] = "";
			$rentProductValue['goodsType'] = $goodsType;
			$rentProductValue['itemType'] = $field[69];			
			$rentProductValue['multiOpt'] = ($field[46] == '1')?'1':'0';
			if($rentProductValue['multiOpt'] == '0') $rentProductValue['tgrade'] = $productoptions[0]['grade'];
			
			$rentProductValue['maincommi'] = $commi["main"];	
			$rentProductValue['trust_vender'] = '';	
			$rentProductValue['trust_approve'] = '';

			$rentProductResult = rentProductSave( $rentProductValue );
			rentProduct::updateOptions($pridx,$productoptions);	

			$sql2 = "insert vender_rent SET ";
			$sql2.= "vender				= '".$vender."', ";
			$sql2.= "pridx				= '".$pridx."', ";
			$sql2.= "pricetype			= '".$field[37]."', ";
			$sql2.= "base_period		= '".$field[38]."', ";
			$sql2.= "ownership			= '".$field[39]."', ";
			$sql2.= "useseason			= '".$field[40]."', ";
			$sql2.= "base_time			= '".$field[41]."', ";
			//$sql2.= "base_price			= '".$field[42]."', ";
			//$sql2.= "timeover_price		= '".$field[43]."', ";
			$sql2.= "halfday			= '".$field[42]."', ";
			//$sql2.= "halfday_percent	= '".$field[45]."', ";
			$sql2.= "oneday_ex			= '".$field[43]."', ";
			//$sql2.= "time_percent		= '".$field[47]."', ";
			$sql2.= "checkin_time		= '".$field[44]."', ";
			$sql2.= "checkout_time		= '".$field[45]."', ";
			$sql2.= "cancel_cont		= '".$field[71]."', ";
			$sql2.= "discount_card		= '".$field[72]."' ";
			mysql_query($sql2,get_db_conn());
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

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function ACodeSendIt(f,obj) {
	if(obj.ctype=="X") {
		f.code.value = obj.value+"000000000";
	} else {
		f.code.value = obj.value;
	}

	burl = "product_excelupload.ctgr.php?depth=2&code=" + obj.value;
	curl = "product_excelupload.ctgr.php?depth=3";
	durl = "product_excelupload.ctgr.php?depth=4";
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
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; ��ǰ �ϰ����� &gt; <span class="2depth_select">��ǰ���� �ϰ� ���</span></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">





			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_excelupload_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN="2" background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�ټ� ��ǰ������ �������Ϸ� ����� �ϰ� ����� �ϴ� ����Դϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN="2" background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_excelupload_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>

			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=mode>
			<input type="hidden" name="code" value="">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��Ͼ�� �ٿ�ε�</TD>
					<TD class="td_con1" ><A HREF="images/sample/product.csv"><img src="images/btn_down1.gif" border=0 align=absmiddle></A> <span class="font_orange">������(CSV)������ �������� �� ������ ���� �ۼ��մϴ�.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>

				<?if($usevender==true) {?>

				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ϻ�ǰ ������ ����</TD>
					<TD class="td_con1" >
					<select name=vender>
						<option value="0">���θ� ����</option>
						<?
						while(list($key,$val)=each($venderlist)) {
							echo "<option value=\"".$val->vender."\">".$val->id." (".$val->com_name.")</option>\n";
						}
						?>
					</select>
					<span class="font_orange">����ǰ�� ��ϵ� �����縦 �����ϼ���.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>

				<?}?>

				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ϻ�ǰ ����</TD>
					<TD class="td_con1" >
					<select name=goodsType>
						<option value="1">�ǸŻ�ǰ</option>
						<option value="2">�뿩��ǰ</option>
					</select>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>

				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ī�װ� ����</TD>
					<TD class="td_con1" >
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
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
						<iframe name="BCodeCtgr" src="product_excelupload.ctgr.php?depth=2" width="145" height="21" scrolling=no frameborder=no></iframe>
						</td>
						<td></td>
						<td><iframe name="CCodeCtgr" src="product_excelupload.ctgr.php?depth=3" width="145" height="21" scrolling=no frameborder=no></iframe></td>
						<td></td>
						<td><iframe name="DCodeCtgr" src="product_excelupload.ctgr.php?depth=4" width="145" height="21" scrolling=no frameborder=no></iframe></td>
					</tr>
					</table>
					</td>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������(CSV) ���</TD>
					<TD class="td_con1" ><input type=file name=upfile style="width:54%" class="input"> <span class="font_orange">������(CSV) ���ϸ� ��� �����մϴ�.</span></TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td align="center" height=10></td>
			</tr>
			<tr>
				<td align="center"><img src="images/btn_fileup.gif" id="uploadButton" width="113" height="38" border="0" style="cursor:hand" onclick="CheckForm(document.form1);"></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
			</tr>

			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ���� �ϰ� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">
						- ��ǰ ��Ͻ� �� ��ǰ�� ���ó���� ���� �ʰ�, �ټ��� ��ǰ������ ����(CSV)���Ϸ� �ۼ��Ͽ� �ϰ� ����ϴ� ����Դϴ�.
						<br>
						<FONT class=font_orange>- 1ȸ ��� ������ ��ǰ���� <B>100�� ���� ��� ����</B>�Ͽ��� 100�� �̻��� ��쿡 ������ ����Ͻñ� �ٶ��ϴ�.</font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">����(CSV)���� �ۼ� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">
						- �������� �ۼ��� �ι� ° ���κ��� �����͸� �Է��Ͻñ� �ٶ��ϴ�. (ù ������ �ʵ� ����κ�)<br>
						- �Ʒ� ���Ĵ�� <FONT class=font_orange><B>�������� �ۼ� -> �ٸ��̸����� ���� -> CSV(��ǥ�� �и�)</B></font> ������ �����Ͻø� �˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ���� �ϰ���� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� �Ʒ��� ������ ����� ��ǰ���� ���������� �ۼ��մϴ�.<br>
						<span class="font_orange" style="padding-left:10px">----------------------------------------------------- ��ǰ���� ���� ���� -----------------------------------------------------</span><br>
						<span class="font_blue" style="padding-left:25px">��ǰ��, ���߰�, �ǸŰ�, ���Ű�, ������, ������, �귣��, �𵨸�, �����, �����ڵ�, ������(��), ���,</span>
						<br>
						<span class="font_blue" style="padding-left:25px">���û���1, ���û���1����, ���û���2, ��������, ū�̹���, �����̹���, �����̹���, �̹����ڵ�����, ��ǰ�󼼼���<span><br>
						<span class="font_orange" style="padding-left:10px">------------------------------------------------------------------------------------------------------------------------------</span><br>

						<div style="padding-left:30">
						<table border=0 cellpadding=0 cellspacing=0 width=90%>
						<col width=150></col>
						<col width=></col>
						<tr>
							<td colspan=2 align=center style="padding-bottom:5">
							<B>��ǰ���� ���� �ۼ� ��)</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ��<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							�Ｚ �ھ�2 ��� 1.66G 14.1���̵� ��Ʈ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���߰�<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							1580000 <img width=20 height=0><FONT class=font_orange>(<B>����</B>�� �Է��ϼ���.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ǸŰ�<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							1350000 <img width=20 height=0><FONT class=font_orange>(<B>����</B>�� �Է��ϼ���.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���Ű�</td>
							<td class=td_con1 style="padding-left:5;">
							1290000 <img width=20 height=0><FONT class=font_orange>(<B>����</B>�� �Է��ϼ���.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">
							�Ｚ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">
							�ѱ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�𵨸�</td>
							<td class=td_con1 style="padding-left:5;">
							YP-S3A
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�귣��</td>
							<td class=td_con1 style="padding-left:5;">
							YEPP
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�����</td>
							<td class=td_con1 style="padding-left:5;">
							<?=DATE("Ymd")?> <img width=10 height=0><FONT class=font_orange>(��ó����, <B>����</B>�� �Է��ϼ���.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�����ڵ�</td>
							<td class=td_con1 style="padding-left:5;">
							N123456789
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15" rowspan="3">������(��)</td>
							<td class=td_con1 style="padding-left:5;"><b>����<FONT class=font_orange>��</font></b> : 11000<br>
							<FONT class=font_orange>* �������� 0���� ũ�� 999999 ���� ���� ���ڷθ� �Է��� �ּ���.<br>
							* �����ݿ� ���� �ʴ� ������ ���� ���� �Ǵ� 0���� ��ϵ˴ϴ�.</font></td>
						</tr>
						<tr><td height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=td_con1 style="padding-left:5;"><b>����<FONT color="#0000FF">��</font></b> : 0.01%<br>
							<FONT class=font_orange>* <B>����</B>�� Ư������ <B>�Ҽ���(.)</B>, <B>�伾Ʈ(%)</B>�� �Է��ϼ���.<br>
							* ������ �Է½� <B>�伾Ʈ(%)</B> ���� �Է��� �ּ���. ���Է½� <B>������</B>���� ��ϵ˴ϴ�.<br>
							* �������� 0���� ũ�� 100���� ���� ���� �Է��� �ּ���.<br>
							* �������� �Ҽ��� ��°�ڸ������� �Է� �����մϴ�.<br>
							* �������� ���Ŀ� ��ġ ���� ���� ���� ��� 0���� ��ϵ˴ϴ�.<br>
							</font></td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							58 <img width=20 height=0><FONT class=font_orange>(<B>����</B> : ������, <B>0</B> : ǰ��, <B>0�̻�</B> : ����)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���û���1</td>
							<td class=td_con1 style="padding-left:5;">
							�ɼ�(RAM)�߰� | 512M�߰� | 1G�߰� <img width=20 height=0><FONT class=font_orange>�Ӽ��� | �Ӽ�(�Ӽ��� "|"�� �����Ͽ� ���)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���û���1 ����</td>
							<td class=td_con1 style="padding-left:5;">
							1380000 | 1410000 <img width=20 height=0><FONT class=font_orange>���û���1 �Ӽ��� ���� ���� (�ǸŰ��� ���õ˴ϴ�.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���û���2</td>
							<td class=td_con1 style="padding-left:5;">
							���� | ���� | �Ķ� | ��� <img width=20 height=0><FONT class=font_orange>�Ӽ��� | �Ӽ�(�Ӽ��� "|"�� �����Ͽ� ���)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ��������<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							Y <img width=20 height=0><FONT class=font_orange>(<B>Y</B> : ��ǰ����, <B>N</B> : �������)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">ū�̹���</td>
							<td class=td_con1 style="padding-left:5;">
							http://www.abc.com/images/product/1000_1.jpg <FONT class=font_orange><B>(gif/jpg �̹����� ����)</B></font>
							<br>
							<FONT class=font_orange>(��ǰ �̹����� �����ϴ� URL�� ��Ȯ�� �Է��Ͻø� �˴ϴ�.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�����̹���</td>
							<td class=td_con1 style="padding-left:5;">
							http://www.abc.com/images/product/1000_2.jpg <FONT class=font_orange><B>(gif/jpg �̹����� ����)</B></font>
							<br>
							<FONT class=font_orange>(<B>�̹����ڵ�����</B>�� ��� �Է����� �ʾƵ� �˴ϴ�.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�����̹���</td>
							<td class=td_con1 style="padding-left:5;">
							http://www.abc.com/images/product/1000_3.jpg <FONT class=font_orange><B>(gif/jpg �̹����� ����)</B></font>
							<br>
							<FONT class=font_orange>(<B>�̹����ڵ�����</B>�� ��� �Է����� �ʾƵ� �˴ϴ�.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�̹����ڵ�����</td>
							<td class=td_con1 style="padding-left:5;">
							Y <img width=20 height=0><FONT class=font_orange>(<B>Y</B> : ū�̹����� ����/�����̹��� �ڵ�����, <B>N</B> : �ڵ���������)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ�󼼼���</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� ���� �󼼼����� �Է��Ͻø� �˴ϴ�.
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���Ű�</td>
							<td class=td_con1 style="padding-left:5;">
							���ڷ� ���Ű� �ݾ��� �Է��Ͻø� �˴ϴ�.
							(�� : 10000)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������������Ұ�</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ���� �Ұ� ��ǰ�� ��� '<strong>Y</strong>' �� �Է��Ͻø� �˴ϴ�. ( ����� ��� ����νø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">����������Ұ�</td>
							<td class=td_con1 style="padding-left:5;">
							������ ���� �Ұ� ��ǰ�� ��� '<strong>Y</strong>' �� �Է��Ͻø� �˴ϴ�. ( ����� ��� ����νø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���Ż���ǰ����Ұ�</td>
							<td class=td_con1 style="padding-left:5;">
							���Ż���ǰ ���� �Ұ� ��ǰ �� ��� '<strong>Y</strong>' �� �Է��Ͻø� �˴ϴ�. ( ����� ��� ����νø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ȯ �� ȯ�ҺҰ�</td>
							<td class=td_con1 style="padding-left:5;">
							��ȯ �� ��ǰ �Ұ� ��ǰ�� ��� '<strong>Y</strong>' �� �Է��Ͻø� �˴ϴ�. ( ����� ��� ����νø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">����Ÿ��</td>
							<td class=td_con1 style="padding-left:5;">�� Ÿ�Ժ� ������ �Է��մϴ�. (24�ð���:day, 1�ð���:time, ������:checkout, �ܱ�Ⱓ��:period, ���Ⱓ��:long)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�⺻�뿩��</td>
							<td class=td_con1 style="padding-left:5;">�⺻�뿩���� �Է��Ͻø� �˴ϴ�. (2��3���� ��� '<strong>3</strong>'�� �Է��Ͻø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������ ������</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� ���Ⱓ��(long)�� ��� ������ �������� �Է��Ͻø� �˴ϴ�. (������ ������������ ��� '<strong>mv</strong>', ������ �ݳ��� ��� '<strong>re</strong>'�� �Է��Ͻø� �˴ϴ�.)</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�������뿩��</td>
							<td class=td_con1 style="padding-left:5;">�������� ��� '<strong>0</strong>', ������� ��� '<strong>1</strong>' �� �Է��մϴ�. </td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ּҽð�</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 1�ð���(time)�� ��� 1~24 ������ ������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���� 12�ð� �뿩���</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�� ��� ����� ��� '<strong>Y</strong>', �������� ��� '<strong>N</strong>'�� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">1���ʰ��� ���ݱ���</td>
							<td class=td_con1 style="padding-left:5;">���ݱ����� 1�ϴ����� ��� '<strong>day</strong>', 12�ð������� ��� '<strong>half</strong>', 1�ð������� ��� '<strong>time</strong>'�� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�뿩����(üũ��)�ð�</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� ������(checkout)�� ���� üũ�νð�, �� �� Ÿ���� ��쿡�� �뿩���۽ð��� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�뿩����(üũ�ƿ�)�ð�</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� ������(checkout)�� ���� üũ�ƿ��ð�, �� �� Ÿ���� ���� �뿩����ð��� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ɼǱ���</td>
							<td class=td_con1 style="padding-left:5;">��ǰ�ɼ��� ���ϻ�ǰ�� ��� '<strong>0</strong>', ���ջ�ǰ�� ��� '<strong>1</strong>'�� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ɼǸ�(�����Ⱓ)</td>
							<td class=td_con1 style="padding-left:5;">�ɼǸ�(���Ⱓ���� ���� �����Ⱓ)�� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ɼǵ��</td>
							<td class=td_con1 style="padding-left:5;">�ɼǺ� ����� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ɼ����󰡰�</td>
							<td class=td_con1 style="padding-left:5;">�ɼǺ� ���󰡰��� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ɼ����</td>
							<td class=td_con1 style="padding-left:5;">�ɼǺ� ����� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">1�ð��ʰ��ð������� �ۼ�Ʈ</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 1�ð���(time)�� ��� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">1�ð��ʰ��ð�����</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 1�ð���(time)�� ��� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">12�ð� ����� �ۼ�Ʈ</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� ���� 12�ð� �뿩����� ��� 24�ð������ �ۼ�Ʈ���� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">12�ð� ���</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� ���� 12�ð� �뿩����� ��� 24�ð������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���� 12�ð� ���ݿ���� �ۼ�Ʈ</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� 1���ʰ��� ���ݱ����� 12�ð�(half)�� ��� 24�ð������ �ۼ�Ʈ���� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���� 12�ð� ���ݿ��</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� 1���ʰ��� ���ݱ����� 12�ð�(half)�� ��� 24�ð������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���� 12�ð� ���ݿ���� �ۼ�Ʈ</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� 1���ʰ��� ���ݱ����� 1�ð�(time)�� ��� 24�ð������ �ۼ�Ʈ���� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���� 12�ð� ���ݿ��</td>
							<td class=td_con1 style="padding-left:5;">����Ÿ���� 24�ð���(day)�̰� 1���ʰ��� ���ݱ����� 1�ð�(time)�� ��� 24�ð������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�г�/�Ͻú�</td>
							<td class=td_con1 style="padding-left:5;">���Ⱓ���� ��� �ɼǺ� �г�/�Ͻú��� �ѱ� �״�� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">���Ⱓ���� ��� �ɼǺ� �������� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">���Ⱓ���� ��� �ɼǺ� �������� �Է��մϴ�. (������ '|' �� ���ϻ�ǰ�� ��쿡�� �Է��մϴ�. )</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�����Ⱑ��</td>
							<td class=td_con1 style="padding-left:5;">�����Ⱑ���� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�������ָ�����</td>
							<td class=td_con1 style="padding-left:5;">�������ָ������� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ؼ����Ⱑ��</td>
							<td class=td_con1 style="padding-left:5;">�ؼ����Ⱑ���� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ؼ������ָ�����</td>
							<td class=td_con1 style="padding-left:5;">�ؼ������ָ������� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ָ�����</td>
							<td class=td_con1 style="padding-left:5;">�ָ������� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���Ͽ��డ�ɿ���</td>
							<td class=td_con1 style="padding-left:5;">���Ͽ��డ���� ��� '<strong>Y</strong>', ���Ͽ���Ұ����� ��� '<strong>N</strong>'�� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰŸ��</td>
							<td class=td_con1 style="padding-left:5;">��ǰŸ���� ��ǰ�� ��� '<strong>product</strong>', ����� ��� '<strong>location</strong>'�� �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ߵ������� �ؾ���</td>
							<td class=td_con1 style="padding-left:5;">�ߵ������� �ؾ��뿡 ���� ������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">����ī������</td>
							<td class=td_con1 style="padding-left:5;">����ī�����ο� ���� ������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ ���1 ����</td>
							<td class=td_con1 style="padding-left:5;">��ǰ ��� ���� �� ������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ ���1 ����</td>
							<td class=td_con1 style="padding-left:5;">��ǰ ��� ���� �� ������ �Է��մϴ�.</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ ���n ����</td>
							<td class=td_con1 style="padding-left:5;" rowspan="3">��ǰ ��������� �׸� ��ŭ ���, ���� �� ���� ���� �߰� �Ͻø� �˴ϴ�.</td>
						</tr>
						<tr><td  height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��ǰ ���n ����</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</div>

						<span class="font_orange" style="padding-left:10px">------------------------------------------------------------------------------------------------------------------------------</span>
						</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� ��ǰ ����� ī�װ��� ���� ��, ����(CSV)������ ���ε� �մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� [���ϵ��] ��ư�� �̿��Ͽ� ���ε� �Ϸ� �ϸ� ���õ� ī�װ��� ��ǰ�� ��ϵ˴ϴ�.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
			</table>
</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
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



<?=$onload?>

<? INCLUDE "copyright.php"; ?>