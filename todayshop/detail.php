<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");

//sns ȫ���� ���� ����
$sid = $_REQUEST["sid"];
$sql = "SELECT id,pcode FROM tblsnsproduct WHERE code='".$sid."'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$sell_memid = ($_ShopInfo->getMemid() != $row->id)? $row->id:"";
}
mysql_free_result($result);

$mode=$_REQUEST["mode"];
$coupon_code=$_REQUEST["coupon_code"];

$code=$_REQUEST["code"];
$productcode=$_REQUEST["productcode"];

$sort=$_REQUEST["sort"];
$brandcode=$_REQUEST["brandcode"];

/* ��ǰ�� ���� ����� �̸��� ���ؼ� ó�� */
$userloginname = 'Guest';
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "select name from tblmember WHERE id='".$_ShopInfo->getMemid()."' limit 1";
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)) $userloginname = mysql_result($res,0,0);
		mysql_free_result($res);
	}
}
$_cdata= $_pdata= NULL;
if(preg_match('/899[0-9]{15}$/',$productcode)){
	$sql = "SELECT * FROM tblproductcode WHERE codeA='899' AND codeB='000' AND codeC='000' AND codeD='000' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_cdata=$row;
		if($row->group_code=="NO") _alert('�ǸŰ� ����� ��ǰ�Դϴ�.','/main/main.php'); //���� �з�
		else if(strlen($row->group_code)>0 && strlen($_ShopInfo->getMemid())==0) _alert('',$Dir.FrontDir."login.php?chUrl=".getUrl());	//ȸ���� ���ٰ���
		else if(strlen($row->group_code)>0 && strpos($row->group_code,$_ShopInfo->getMemgroup())===false) _alert('���ٱ����� �����ϴ�.','-1'); 	//�׷�ȸ���� ����

		//Wishlist ���
		if($mode=="wishlist") {
			if(strlen($_ShopInfo->getMemid())==0) _alert('�α����� �ϼž� �� ���񽺸� �̿��Ͻ� �� �ֽ��ϴ�.',$Dir.FrontDir."login.php?chUrl=".getUrl());	//��ȸ��

			$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
			$result2=mysql_query($sql,get_db_conn());
			$row2=mysql_fetch_object($result2);
			$totcnt=$row2->totcnt;
			mysql_free_result($result2);
			$maxcnt=20;
			if($totcnt>=$maxcnt) {
				$sql = "SELECT b.productcode ";
				$sql.= "FROM tblwishlist a, tblproduct b ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
				$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
				$sql.= "AND b.display='Y' ";
				$sql.= "AND (b.group_check='N' OR c.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
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
					$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode NOT IN (".$wishprcode.") ";
					mysql_query($sql,get_db_conn());
				}
			}
			if($totcnt<$maxcnt) {
				$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode='".$productcode."' ";
				$result2=mysql_query($sql,get_db_conn());
				$row2=mysql_fetch_object($result2);
				$cnt=$row2->cnt;
				mysql_free_result($result2);
				if($cnt>0) {
					echo "<html></head><body onload=\"alert('WishList�� �̹� ��ϵ� ��ǰ�Դϴ�.');history.go(-1);\"></body></html>";exit;
				} else {
					$sql = "INSERT tblwishlist SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "productcode	= '".$productcode."', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('WishList�� �ش� ��ǰ�� ����Ͽ����ϴ�.');history.go(-1);\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('WishList���� ".$maxcnt."�� ������ ����� �����մϴ�.\\n\\nWishList���� �ٸ� ��ǰ�� �����Ͻ� �� ����Ͻñ� �ٶ��ϴ�.');history.go(-1);\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� �з��� �������� �ʽ��ϴ�.');location.href='".$Dir.MainDir."main.php';\"></body></html>";exit;
	}
	mysql_free_result($result);

	$sql = "SELECT a.* ";
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	//�Ҽ�
	if(eregi("S",$_cdata->type)) {
		$sql = "SELECT a.*, c.* ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "LEFT OUTER JOIN tblproduct_social c ON a.productcode=c.pcode ";
	}
	$sql.= "WHERE a.productcode='".$productcode."' AND a.display='Y' ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";


	$sql = "select a.*,t.*,unix_timestamp(t.end) -unix_timestamp() as remain, t.salecnt+t.addquantity as sellcnt from tblproduct a inner join todaysale t using(pridx) LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode WHERE a.productcode='".$productcode."' AND a.display='Y' AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') limit 1";
	$result=mysql_query($sql,get_db_conn());

	if($row=mysql_fetch_object($result)){
		$_pdata=$row;
		$sql = "SELECT * FROM tblproductbrand ";
		$sql.= "WHERE bridx='".$_pdata->brand."' ";
		$bresult=mysql_query($sql,get_db_conn());
		$brow=mysql_fetch_object($bresult);
		$_pdata->brandcode = $_pdata->brand;
		$_pdata->brand = $brow->brandname;

		mysql_free_result($result);

		if($_pdata->assembleuse=="Y") {
			$sql = "SELECT * FROM tblassembleproduct ";
			$sql.= "WHERE productcode='".$productcode."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=@mysql_fetch_object($result)) {
				$_adata=$row;
				mysql_free_result($result);
				$assemble_list_pridx = str_replace("","",$_adata->assemble_list);

				if(strlen($assemble_list_pridx)>0) {
					$sql = "SELECT pridx,productcode,productname,sellprice,quantity,tinyimage FROM tblproduct ";
					$sql.= "WHERE pridx IN ('".str_replace(",","','",$assemble_list_pridx)."') ";
					$sql.= "AND assembleuse!='Y' ";
					$sql.= "AND display='Y' ";
					$result=mysql_query($sql,get_db_conn());
					while($row=@mysql_fetch_object($result)) {
						$_acdata[$row->pridx] = $row;
					}
					mysql_free_result($result);
				}
			}
		}
		$_pdata->checkAbles = _getEtcImg($_pdata->productcode,'val'); // ��� �Ұ� �׸� ���� ���� �߰�
	} else {
		_alert("�ش� ��ǰ ������ �������� �ʽ��ϴ�.1",'-1');
	}
} else {
	_alert("�ش� ��ǰ ������ �������� �ʽ��ϴ�.2",$Dir.MainDir."main.php");
}

if($mode=="coupon" && strlen($coupon_code)==8 && strlen($productcode)==18) {	//���� �߱�
	if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
		echo "<html></head><body onload=\"alert('�α��� �� ���� �ٿ�ε尡 �����մϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	} else {
		$sql = "SELECT * FROM tblcouponinfo ";
		if($_pdata->vender>0) {
			$sql.= "WHERE (vender='0' OR vender='".$_pdata->vender."') ";
		} else {
			$sql.= "WHERE vender='0' ";
		}
		$sql.= "AND coupon_code='".$coupon_code."' ";
		$sql.= "AND display='Y' AND issue_type='Y' AND detail_auto='Y' ";
		$sql.= "AND (date_end>".date("YmdH")." OR date_end='') ";
		$sql.= "AND ((use_con_type2='Y' AND productcode IN ('ALL','".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."')) OR (use_con_type2='N' AND productcode NOT IN ('".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."'))) ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
				$onload="<script>alert(\"��� ������ �߱޵Ǿ����ϴ�.\");</script>";
			} else {
				$date=date("YmdHis");
				if($row->date_start>0) {
					$date_start=$row->date_start;
					$date_end=$row->date_end;
				} else {
					$date_start = substr($date,0,10);
					$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
				}
				$sql = "INSERT tblcouponissue SET ";
				$sql.= "coupon_code	= '".$coupon_code."', ";
				$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
				$sql.= "date_start	= '".$date_start."', ";
				$sql.= "date_end	= '".$date_end."', ";
				$sql.= "date		= '".$date."' ";
				mysql_query($sql,get_db_conn());
				if(!mysql_errno()) {
					$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 ";
					$sql.= "WHERE coupon_code = '".$coupon_code."'";
					mysql_query($sql,get_db_conn());

					$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
				} else {
					if($row->repeat_id=="Y") {	//������ ��߱��� �����ϴٸ�,,,,
						$sql = "UPDATE tblcouponissue SET ";
						if($row->date_start<=0) {
							$sql.= "date_start	= '".$date_start."', ";
							$sql.= "date_end	= '".$date_end."', ";
						}
						$sql.= "used		= 'N' ";
						$sql.= "WHERE coupon_code='".$coupon_code."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn());
						$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
					} else {
						$onload="<script>alert(\"�̹� ������ �߱޹����̽��ϴ�.\\n\\n�ش� ������ ��߱��� �Ұ����մϴ�.\");</script>";
					}
				}
			}
		} else {
			$onload="<script>alert(\"�ش� ������ ��� ������ ������ �ƴմϴ�.\");</script>";
		}
		mysql_free_result($result);
	}
}

$ref=$_REQUEST["ref"];
if (strlen($ref)==0) {
	$ref=strtolower(ereg_replace("http://","",getenv("HTTP_REFERER")));
	if(strpos($ref,"/") != false) $ref=substr($ref,0,strpos($ref,"/"));
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
	$viewproduct=$_COOKIE["ViewProduct"];
	if(strrpos(" ".$viewproduct,",".$productcode.",")==0) {
		if(strlen($viewproduct)==0) {
			$viewproduct=",".$productcode.",";
		} else {
			$viewproduct=",".$productcode.$viewproduct;
		}
	} else {
		$viewproduct=str_replace(",".$productcode.",",",",$viewproduct);
		$viewproduct=",".$productcode.$viewproduct;
	}
	$viewproduct=substr($viewproduct,0,571);
	setcookie("ViewProduct",$viewproduct,0,"/".RootPath);
}


//��ǰ �� ���� �̺�Ʈ ����
if(strlen($_cdata->detail_type)==5) {	//������������ �ƴ� ���
	$sql = "SELECT * FROM ".$designnewpageTables." WHERE type='detailimg' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$row->body=str_replace("[DIR]",$Dir,$row->body);
		$design_type=$row->code;
		$detailimg_eventloc=$row->leftmenu;
		$detailimg_body="<table border=0 cellpadding=0 cellspacing=0>\n";
		if($design_type=="1") {	//�̹��� Ÿ��
			$detailimg_body.="<tr><td align=center><img src=\"".$Dir.DataDir."shopimages/etc/".$row->filename."\" border=0></td></tr>\n";
		} else if($design_type=="2") {	//html Ÿ��
			$detailimg_body.="<tr><td align=center>".$row->body."</td></tr>\n";
		}
		$detailimg_body.="</table>\n";
	}
	mysql_free_result($result);
}


//��ǰ ������ ��������
if(strlen($_data->exposed_list)==0) {
	$_data->exposed_list=",0,2,3,4,5,6,7,19,";
}
$arexcel = explode(",",substr($_data->exposed_list,1,-1));
$prcnt = count($arexcel);
$arproduct=array(&$prproduction,&$prmadein,&$prconsumerprice,&$prsellprice,&$prreserve,&$praddcode,&$prquantity,&$proption,&$prproductname,&$prdollarprice,&$prmodel,&$propendate,&$pruserspec0,&$pruserspec1,&$pruserspec2,&$pruserspec3,&$pruserspec4,&$prbrand,&$prselfcode,&$prpackage);
$ardollar=explode(",",$_data->ETCTYPE["DOLLAR"]);

if(strlen($ardollar[1])==0 || $ardollar[1]<=0) $ardollar[1]=1;

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
		if (substr($etctemp[$i],0,11)=="DELIINFONO=")	$deliinfono=substr($etctemp[$i],11);
	}
}

//���/��ȯ/ȯ������ ����
$deli_info="";
if($deliinfono!="Y") {	//������ǰ�� ���/��ȯ/ȯ������ ������ ���
	$deli_info_data="";
	if($_pdata->vender>0) {	//������ü ��ǰ�̸� ������ü ���/��ȯ/ȯ������ ����
		$deli_info_data=$_vdata->deli_info;
		$aboutdeliinfofile=$Dir.DataDir."shopimages/vender/aboutdeliinfo_".$_vdata->vender.".gif";
	} else {
		$deli_info_data=$_data->deli_info;
		$aboutdeliinfofile=$Dir.DataDir."shopimages/etc/aboutdeliinfo.gif";
	}
	if(strlen($deli_info_data)>0) {
		$tempdeli_info=explode("=",$deli_info_data);
		if($tempdeli_info[0]=="Y") {
			if($tempdeli_info[1]=="TEXT") {			//�ؽ�Ʈ��
				$allowedTags = "<h1><b><i><a><ul><li><pre><hr><blockquote><u><img><br><font>";

				if(strlen($tempdeli_info[2])>0 || strlen($tempdeli_info[3])>0) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr>\n";
					$deli_info.= "	<td style=\"padding:10,15,10,15\">\n";
					$deli_info.= "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					if(strlen($tempdeli_info[2])>0) {	//������� �ؽ�Ʈ
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td><img src=\"".$Dir."images/common/detaildeliinfo_img1.gif\" border=0></td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td style=\"line-height:14pt;padding-left:10\">\n";
						$deli_info.= "		".nl2br(strip_tags($tempdeli_info[2],$allowedTags))."\n";
						$deli_info.= "		</td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr><td height=15></td></tr>\n";
					}
					if(strlen($tempdeli_info[3])>0) {	//��ȯ/ȯ������ �ؽ�Ʈ
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td><img src=\"".$Dir."images/common/detaildeliinfo_img2.gif\" border=0></td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td style=\"line-height:14pt;padding-left:10\">\n";
						$deli_info.= "		".nl2br(strip_tags($tempdeli_info[3],$allowedTags))."\n";
						$deli_info.= "		</td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr><td height=15></td></tr>\n";
					}
					$deli_info.= "	</table>\n";
					$deli_info.= "	</td>\n";
					$deli_info.= "</tr>\n";
					$deli_info.= "</table>\n";
				}
			} else if($tempdeli_info[1]=="IMAGE") {	//�̹�����
				if(file_exists($aboutdeliinfofile)) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr>\n";
					$deli_info.= "	<td align=center><img src=\"".$aboutdeliinfofile."\" align=absmiddle border=0></td>\n";
					$deli_info.= "</tr>\n";
					$deli_info.= "</table>\n";
				}
			} else if($tempdeli_info[1]=="HTML") {	//HTML�� �Է�
				if(strlen($tempdeli_info[2])>0) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr><td>".$tempdeli_info[2]."</td></tr>\n";
					$deli_info.= "</table>\n";
				}
			}
		}
	}
}

//������� ȯ�� ����
$reviewlist=$_data->ETCTYPE["REVIEWLIST"];
$reviewdate=$_data->ETCTYPE["REVIEWDATE"];
if(strlen($reviewlist)==0) $reviewlist="N";

if($mode=="review_write") {
	function ReviewFilter($filter,$memo,&$findFilter) {
		$use_filter = split(",",$filter);
		$isFilter = false;
		for($i=0;$i<count($use_filter);$i++) {
			if (eregi($use_filter[$i],$memo)) {
				$findFilter = $use_filter[$i];
				$isFilter = true;
				break;
			}
		}
		return $isFilter;
	}

	$rname=$_POST["rname"];
	$rcontent=$_POST["rcontent"];
	$rmarks=$_POST["rmarks"];
	if((strlen($_ShopInfo->getMemid())==0) && $_data->review_memtype=="Y") {
		echo "<html></head><body onload=\"alert('�α����� �ϼž� ����ı� ����� �����մϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."'\"></body></html>";exit;
	}
	if(strlen($review_filter)>0) {	//����ı� ���� ���͸�
		if(ReviewFilter($review_filter,$rcontent,$findFilter)) {
			echo "<html></head><body onload=\"alert('����Ͻ� �� ���� �ܾ �Է��ϼ̽��ϴ�.(".$findFilter.")\\n\\n�ٽ� �Է��Ͻñ� �ٶ��ϴ�.');history.go(-1);\"></body></html>";exit;
		}
	}
	/** ÷�� �̹��� �߰� */
	$up_imd = '';

	if(is_array($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])){
		if($_FILES['img']['error'] > 0){
			echo "<html></head><body onload=\"alert('���� ���ε��� ������ �߻��߽��ϴ�.');history.go(-1);\"></body></html>";
			exit;
		}

		$save_dir=$Dir.DataDir."shopimages/productreview/";
		$numresult = mysql_query("select ifnull(max(num),1) as num from tblproductreview",get_db_conn());

		if($numresult){
			$file_name =  $productcode.(intval(mysql_result($numresult,0,0))+1);
		}

		$size=getimageSize($_FILES['img']['tmp_name']);
		$width=$size[0];
		$height=$size[1];
		$imgtype=$size[2];
		$_w = 650;
		$ratio = ($_w > 0 && $width > $_w)?(real)($_w / $width):1;

		if($imgtype==1)      $file_ext ='gif';
		else if($imgtype==2) $file_ext ='jpg';
		else if($imgtype==3) $file_ext ='png';
		else{
			 echo "<html></head><body onload=\"alert('�ùٸ� ������ �̹��� ������ �ƴմϴ�.');history.go(-1);\"></body></html>";
			 exit;
		}

		$index = 0;
		$up_name = $file_name.".".$file_ext;
		while(file_exists($save_dir."/".$up_name)){
			$up_name = $file_name."_".$index.".".$file_ext;
			$index++;
		}

		if(!move_uploaded_file($_FILES['img']['tmp_name'],$save_dir."/".$up_name)){
			echo "<html></head><body onload=\"alert('���� ���� ����.');history.go(-1);\"></body></html>";
			exit;
		}
		if($ratio < 1){
			$source = $target = $save_dir."/".$up_name;
			$new_width = (int)($ratio*$width);
			$new_height = (int)($ratio*$height);

			$dest_img = imagecreatetruecolor($new_width,$new_height);
			$white = imagecolorallocate($dest_img,255,255,255);
			imagefill($dest_img,0,0,$white);

			if($file_ext == 'gif'){ //�̹��� Ÿ�Կ� ���� �̹��� �ε�
				$src_img = imagecreatefromgif($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagegif($dest_img,$target);
			}else if($file_ext == 'jpg'){
				$src_img = @imagecreatefromjpeg($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagejpeg($dest_img,$target,75);
			}else if($file_ext == 'png'){
				$src_img = imagecreatefrompng($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagepng($dest_img,$target);
			}
			imagedestroy($dest_img);
		}
	}



	$sql = "INSERT tblproductreview SET ";
	$sql.= "productcode	= '".$productcode."', ";
	$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
	$sql.= "name		= '".$rname."', ";
	$sql.= "marks		= '".$rmarks."', ";
	$sql.= "date		= '".date("YmdHis")."', ";
	$sql.= "content		= '".$rcontent."', ";
	$sql.= "img		= '".$up_name."' ";
	mysql_query($sql,get_db_conn());

	if($_data->review_type=="A") $msg="������ ������ ��ϵ˴ϴ�.";
	else $msg="��ϵǾ����ϴ�.";
	$rqry="productcode=".$productcode;
	if(strlen($code)>0) $rqry.="&code=".$code;
	if(strlen($sort)>0) $rqry.="&sort=".$sort;
	if(strlen($brandcode)>0) $rqry.="&brandcode=".$brandcode;
	echo "<html></head><body onload=\"alert('".$msg."');location='".$_SERVER["PHP_SELF"]."?".$rqry."'\"></body></html>";exit;
}

//����/���� ��ǰ ����
$qry = "WHERE 1=1 ";
if(eregi("T",$_cdata->type)) {	//����з�
	$sql = "SELECT productcode FROM tblproducttheme WHERE code LIKE '".$likecode."%' ";
	$result=mysql_query($sql,get_db_conn());
	$t_prcode="";
	while($row=mysql_fetch_object($result)) {
		$t_prcode.=$row->productcode.",";
		$i++;
	}
	mysql_free_result($result);
	$t_prcode=substr($t_prcode,0,-1);
	$t_prcode=ereg_replace(',','\',\'',$t_prcode);
	$qry.= "AND a.productcode IN ('".$t_prcode."') ";

	$add_query="&code=".$code;
} else {	//�Ϲݺз�
	$qry.= "AND a.productcode LIKE '".$likecode."%' ";
}
$qry.= "AND a.display='Y' ";

$tmp_sort=explode("_",$sort);
if($brandcode>0) {
	$qry.="AND a.brand='".$brandcode."' ";
	$add_query.="&brandcode=".$brandcode;
	$brand_link = "brandcode=".$brandcode."&";

	$sql ="SELECT SUBSTRING(a.productcode, 1, 3) AS code FROM tblproduct AS a ";
	$sql.="LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.="WHERE a.display='Y' AND a.brand='".$brandcode."' ";
	$sql.="AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	$sql.="GROUP BY code ";
	$result=mysql_query($sql,get_db_conn());
	$brand_qry = "";
	$leftcode = array();
	while($row=mysql_fetch_object($result)) {
		$leftcode[] = $row->code;
	}
	if(count($leftcode)>0) {
		$brand_qry = "AND codeA IN ('".implode("','",$leftcode)."') ";
	}

	if($tmp_sort[0]=="reserve") {
		$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
	}
	$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
	$sql.= "a.tinyimage, a.date, a.etctype, a.option_price ";
	$sql.= $addsortsql;
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry." ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
	else $sql.= "ORDER BY a.productname ";
} else {
	if($tmp_sort[0]=="reserve") {
		$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
	}
	$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
	if($_cdata->sort=="date2") $sql.="IF(a.quantity<=0,'11111111111111',a.date) as date, ";
	$sql.= "a.tinyimage, a.etctype, a.option_price ";
	$sql.= $addsortsql;
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry." ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="sellprice") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
	else {
		if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
			$sql.= "ORDER BY date DESC ";
		} else if($_cdata->sort=="productname") {
			$sql.= "ORDER BY a.productname ";
		} else if($_cdata->sort=="production") {
			$sql.= "ORDER BY a.production ";
		} else if($_cdata->sort=="price") {
			$sql.= "ORDER BY a.sellprice ";
		}
	}
}
$result=mysql_query($sql,get_db_conn());
unset($arr_productcode);
$isprcode=false;
while($row=mysql_fetch_object($result)) {
	if($productcode==$row->productcode) {
		$isprcode=true;
	} else {
		if($isprcode==false) {
			$arr_productcode["prev"]=$row->productcode;
		} else {
			$arr_productcode["next"]=$row->productcode;
			break;
		}
	}
}
mysql_free_result($result);

//��ǰQNA �Խ��� ���翩�� Ȯ�� �� �������� Ȯ��
$prqnaboard=getEtcfield($_data->etcfield,"PRQNA");
if(strlen($prqnaboard)>0) {
	$sql = "SELECT * FROM tblboardadmin WHERE board='".$prqnaboard."' ";
	$result=mysql_query($sql,get_db_conn());
	$qnasetup=mysql_fetch_object($result);
	mysql_free_result($result);
	if($qnasetup->use_hidden=="Y") unset($qnasetup);
}

//���̽��� �̹���
if(strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)) {
	$fbThumb = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/product/".$_pdata->tinyimage;
}else{
	$fbThumb = "http://".$_ShopInfo->getShopurl()."images/no_img/no_img.gif";
}

//sns ����
$arSnsType = explode("", $_data->sns_reserve_type);
$odrChk = true;
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shopname." [".$_pdata->productname."]"?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<link type="text/css" rel="stylesheet" href="/css/common.css" >

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
var $j =jQuery.noConflict();
</script>
<? include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ClipCopy(url) {
	var tmp;
	tmp = window.clipboardData.setData('Text', url);
	if(tmp) {
		alert('�ּҰ� ����Ǿ����ϴ�.');
	}
}

<?if($_pdata->vender>0){?>
function custRegistMinishop() {
	if(document.custregminiform.memberlogin.value!="Y") {
		alert("�α��� �� �̿��� �����մϴ�.");
		return;
	}
	owin=window.open("about:blank","miniregpop","width=100,height=100,scrollbars=no");
	owin.focus();
	document.custregminiform.target="miniregpop";
	document.custregminiform.action="minishop.regist.pop.php";
	document.custregminiform.submit();
}
<?}?>

function primage_view(img,type) {
	if (img.length==0) {
		alert("Ȯ�뺸�� �̹����� �����ϴ�.");
		return;
	}
	var tmp = "height=350,width=450,toolbar=no,menubar=no,resizable=no,status=no";
	if(type=="1") {
		tmp+=",scrollbars=yes";
		sc="yes";
	} else {
		sc="";
	}
	url = "<?=$Dir.FrontDir?>primage_view.php?scroll="+sc+"&image="+img;

	window.open(url,"primage_view",tmp);
}

function change_quantity(gbn) {
	tmp=document.form1.quantity.value;
	if(gbn=="up") {
		tmp++;
	} else if(gbn=="dn") {
		if(tmp>1) tmp--;
	}
	if(document.form1.quantity.value!=tmp) {
	<? if($_pdata->assembleuse=="Y") { ?>
		if(getQuantityCheck(tmp)) {
			if(document.form1.assemblequantity) {
				document.form1.assemblequantity.value=tmp;
			}
			document.form1.quantity.value=tmp;
			setTotalPrice(tmp);
		} else {
			alert('������ǰ �� '+tmp+'���� ����� ������ ��ǰ�־ ������ �Ұ��մϴ�.');
			return;
		}
	<? } else { ?>
		document.form1.quantity.value=tmp;
	<? } ?>
	}
}

function check_login() {
	if(confirm("�α����� �ʿ��� �����Դϴ�. �α����� �Ͻðڽ��ϱ�?")) {
		document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
	}
}

function review_write() {

}


<?if($_data->coupon_ok=="Y") {?>
function issue_coupon(coupon_code){
	document.couponform.mode.value="coupon";
	document.couponform.coupon_code.value=coupon_code;
	document.couponform.submit();
}
<?}?>



function CheckForm(gbn,temp2) {

	if(gbn!="wishlist") {
		if(document.form1.quantity.value.length==0 || document.form1.quantity.value==0) {
			alert("�ֹ������� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		}
		if(!IsNumeric(document.form1.quantity.value)) {
			alert("�ֹ������� ���ڸ� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		}
		if(miniq>1 && document.form1.quantity.value<=1) {
			alert("�ش� ��ǰ�� ���ż����� "+miniq+"�� �̻� �ֹ��� �����մϴ�.");
			document.form1.quantity.focus();
			return;
		}
	}
	if(gbn=="ordernow") {
		document.form1.ordertype.value="ordernow";
	}
	else if(gbn=="ordernow2" || gbn=="ordernow3") {

		document.form1.ordertype.value=gbn;
		document.form1.action = "<?=$Dir.FrontDir?>basket2.php";
	}
	/*
	else if(gbn=="ordernow4" || gbn=="present" || gbn=="pester") {
		document.form1.ordertype.value=gbn;
		document.form1.action = "<?=$Dir.FrontDir?>basket.php";
	}*/
	else if(gbn=="ordernow4") {
		document.form1.ordertype.value=gbn;
		document.form1.action = "<?=$Dir.FrontDir?>basket3.php";
	}else if(gbn=="ordernow4" || gbn=="present" || gbn=="pester") {
		document.form1.ordertype.value=gbn;
		document.form1.action = "<?=$Dir.FrontDir?>basket.php";
	}

	if(temp2!="") {
		document.form1.opts.value="";
		try {
			for(i=0;i<temp2;i++) {
				if(document.form1.optselect[i].value==1 && document.form1.mulopt[i].selectedIndex==0) {
					alert('�ʼ����� �׸��Դϴ�. �ɼ��� �ݵ�� �����ϼ���');
					document.form1.mulopt[i].focus();
					return;
				}
				document.form1.opts.value+=document.form1.mulopt[i].selectedIndex+",";
			}
		} catch (e) {}
	}
<?
if(eregi("S",$_cdata->type)) {
?>
	if(typeof(document.form1.option)!="undefined" && document.form1.option.selectedIndex<2) {
		alert('�ش� ��ǰ�� �ɼ��� �����ϼ���.');
		document.form1.option.focus();
		return;
	}
	if(typeof(document.form1.option)!="undefined" && document.form1.option.selectedIndex>=2) {
		arselOpt=document.form1.option.value.split("_");
		arselOpt[1] = (arselOpt[1] > 0)? arselOpt[1] :1;
		seq = parseInt(10*(arselOpt[1]-1)) + parseInt(arselOpt[0]);
		if(num[seq-1]==0) {
			alert('�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� �ɼ��� �����ϼ���');
			document.form1.option.focus();
			return;
		}
		document.form1.option1.value = arselOpt[0];
		document.form1.option2.value = arselOpt[1];
	}
<?
}else{
?>
	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex<2) {
		alert('�ش� ��ǰ�� �ɼ��� �����ϼ���.');
		document.form1.option1.focus();
		return;
	}
	if(typeof(document.form1.option2)!="undefined" && document.form1.option2.selectedIndex<2) {
		alert('�ش� ��ǰ�� �ɼ��� �����ϼ���.');
		document.form1.option2.focus();
		return;
	}
	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex>=2) {
		temp2=document.form1.option1.selectedIndex-1;
		if(typeof(document.form1.option2)=="undefined") temp3=1;
		else temp3=document.form1.option2.selectedIndex-1;
		if(num[(temp3-1)*10+(temp2-1)]==0) {
			alert('�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� �ɼ��� �����ϼ���');
			document.form1.option1.focus();
			return;
		}
	}
<?
}
?>
	if(typeof(document.form1.package_type)!="undefined" && typeof(document.form1.packagenum)!="undefined" && document.form1.package_type.value=="Y" && document.form1.packagenum.selectedIndex<2) {
		alert('�ش� ��ǰ�� ��Ű���� �����ϼ���.');
		document.form1.packagenum.focus();
		return;
	}
	if(gbn!="wishlist") {
		<? if($_pdata->assembleuse=="Y") { ?>
		if(typeof(document.form1.assemble_type)=="undefined") {
			alert('���� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. ���Ű� �Ұ����մϴ�.');
			return;
		} else {
			if(document.form1.assemble_type.value.length>0) {
				arracassembletype = document.form1.assemble_type.value.split("|");
				document.form1.assemble_list.value="";

				for(var i=1; i<=arracassembletype.length; i++) {
					if(arracassembletype[i]=="Y") {
						if(document.getElementById("acassemble"+i).options.length<2) {
							alert('�ʼ� ������ǰ�� ��ǰ�� ��� ���Ű� �Ұ����մϴ�.');
							document.getElementById("acassemble"+i).focus();
							return;
						} else if(document.getElementById("acassemble"+i).value.length==0) {
							alert('�ʼ� ������ǰ�� ������ �ּ���.');
							document.getElementById("acassemble"+i).focus();
							return;
						}
					}

					if(document.getElementById("acassemble"+i)) {
						if(document.getElementById("acassemble"+i).value.length>0) {
							arracassemblelist = document.getElementById("acassemble"+i).value.split("|");
							document.form1.assemble_list.value += "|"+arracassemblelist[0];
						} else {
							document.form1.assemble_list.value += "|";
						}
					}
				}
			} else {
				alert('���� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. ���Ű� �Ұ����մϴ�.');
				return;
			}
		}
		<? } ?>
		document.form1.submit();
	} else {
		document.wishform.opts.value=document.form1.opts.value;
		if(typeof(document.form1.option1)!="undefined") document.wishform.option1.value=document.form1.option1.value;
		if(typeof(document.form1.option2)!="undefined") document.wishform.option2.value=document.form1.option2.value;

		window.open("about:blank","confirmwishlist","width=500,height=250,scrollbars=no");
		document.wishform.submit();
	}
}

function view_review(cnt) {
	if(typeof(document.all.reviewspan)=="object" && typeof(document.all.reviewspan.length)!="undefined") {
		for(i=0;i<document.all.reviewspan.length;i++) {
			if(cnt==i) {
				if(document.all.reviewspan[i].style.display=="none") {
					document.all.reviewspan[i].style.display="block";
				} else {
					document.all.reviewspan[i].style.display="none";
				}
			} else {
				document.all.reviewspan[i].style.display="none";
			}
		}
	} else {
		if(document.all.reviewspan.style.display=="none") {
			document.all.reviewspan.style.display="block";
		} else {
			document.all.reviewspan.style.display="none";
		}
	}
}

function review_open(prcode,num) {
	window.open("<?=$Dir.FrontDir?>review_popup.php?prcode="+prcode+"&num="+num,"","width=450,height=400,scrollbars=yes");
}

function review_write() {
	if(typeof(document.all["reviewwrite"])=="object") {
		if(document.all["reviewwrite"].style.display=="none") {
			document.all["reviewwrite"].style.display="";
		} else {
			document.all["reviewwrite"].style.display="none";
		}
	}
}

function CheckReview() {
	if(document.reviewform.rname.value.length==0) {
		alert("�ۼ��� �̸��� �Է��ϼ���.");
		document.reviewform.rname.focus();
		return;
	}
	if(document.reviewform.rcontent.value.length==0) {
		alert("����ı� ������ �Է��ϼ���.");
		document.reviewform.rcontent.focus();
		return;
	}
	document.reviewform.mode.value="review_write";
	document.reviewform.submit();
}

var view_qnano="";
function view_qnacontent(idx) {
	if (idx=="W") {	//������� ����
		alert("��ǰQ&A �Խ��� ���� ������ �����ϴ�.");
	} else if(idx=="N") {	//�ϱ���� ����
		alert("�ش� Q&A�Խ��� �Խñ��� ���� �� �����ϴ�.");
	} else if(idx=="S") {	//��ݱ�� ������ ��
		if(view_qnano.length>0 && view_qnano!=idx) {
			document.all["qnacontent"+view_qnano].style.display="none";
		}
		alert("�ش� ���� ���� ��ݱ���� ������ �Խñ۷�\n\n���� �Խ��ǿ� ���ż� Ȯ���ϼž� �մϴ�.");
	} else if(idx=="D") {
		if(view_qnano.length>0 && view_qnano!=idx) {
			document.all["qnacontent"+view_qnano].style.display="none";
		}
		alert("�ۼ��ڰ� ������ �Խñ��Դϴ�.");
	} else {
		try {
			if(document.all["qnacontent"+idx].style.display=="none") {
				view_qnano=idx;
				document.all["qnacontent"+idx].style.display="";
			} else {
				document.all["qnacontent"+idx].style.display="none";
			}
		} catch (e) {
			alert("������ ���Ͽ� �Խó����� ���� �� �����ϴ�.");
		}
	}
}

function GoPage(gbn,block,gotopage) {
	document.idxform.action=document.idxform.action+"?#"+gbn;
	if(gbn=="review") {
		document.idxform.block.value=block;
		document.idxform.gotopage.value=gotopage;
	} else if(gbn=="prqna") {
		document.idxform.qnablock.value=block;
		document.idxform.qnagotopage.value=gotopage;
	}
	document.idxform.submit();
}

/* ################ �±װ��� ################## */
var IE = false ;
if (window.navigator.appName.indexOf("Explorer") !=-1) {
	IE = true;
}
//tag ��Ģ ���� (%, &, +, <, >, ?, /, \, ', ", =,  \n)
var restrictedTagChars = /[\x25\x26\x2b\x3c\x3e\x3f\x2f\x5c\x27\x22\x3d\x2c\x20]|(\x5c\x6e)/g;
function check_tagvalidate(aEvent, input) {
	var keynum;
	if(typeof aEvent=="undefined") aEvent=window.event;
	if(IE) {
		keynum = aEvent.keyCode;
	} else {
		keynum = aEvent.which;
	}
	//  %, &, +, -, ., /, <, >, ?, \n, \ |
	var ret = input.value;
	if(ret.match(restrictedTagChars) != null ) {
		 ret = ret.replace(restrictedTagChars, "");
		 input.value=ret;
	}
}

function tagCheck(productcode) {
<?if(strlen($_ShopInfo->getMemid())>0){?>
	var obj = document.all;
	if(obj.searchtagname.value.length < 2 ){
		alert("�±׸�(2�� �̻�) �Է��� �ּ���!");
		obj.searchtagname.focus();
		return;
	}
	goProc("prtagreg",productcode);
	return;
<?}else{?>
	alert("�α��� �� �ۼ��� �ּ���!");
	return;
<?}?>
}

function goProc(mode,productcode){
	var obj = document.all;
	if(mode=="prtagreg") {
		succFun=myFunction;
		var tag=obj.searchtagname.value;
		var path="<?=$Dir.FrontDir?>tag.xml.php?mode="+mode+"&productcode="+productcode+"&tagname="+tag;
		obj.searchtagname.value="ó���� �Դϴ�!";
	} else {
		succFun=prTaglist;
		var path="<?=$Dir.FrontDir?>tag.xml.php?mode="+mode+"&productcode="+productcode;
	}
	var myajax = new Ajax(path,
							{
								onComplete: function(text) {
									succFun(text,productcode);
								}
							}
	).request();
}

function myFunction(request,productcode){
	var msgtmp = request;
	var splitString = msgtmp.split("|");

	//�ٽ� �ʱ�ȭ
	var obj = document.all;
	obj.searchtagname.value="";
	if(splitString[0]=="OK") {
		var tag = splitString[2];
		if(splitString[1]=="0") {

		} else if(splitString[1]=="1") {
			goProc("prtagget",productcode);
		}
	} else if(splitString[0]=="NO") {
		alert(splitString[1]);
	}
}

function prTaglist(request) {
	var msgtmp = request;
	var splitString = msgtmp.split("|");
	if(splitString[0]=="OK") {
		document.all["prtaglist"].innerHTML=splitString[1];
	} else if(splitString[0]=="NO") {
		alert(splitString[1]);
	}
}

<? if($_pdata->assembleuse=="Y") { ?>
var currentSelectIndex = "";
function setCurrentSelect(thisSelectIndex) {
	currentSelectIndex = thisSelectIndex;
}

function setAssenbleChange(thisObj,idxValue) {
	if(thisObj.value.length>0) {
		thisValueSplit = thisObj.value.split('|');
		if(thisValueSplit[1].length>0) {
			if(Number(thisValueSplit[1])==0) {
				alert('���� ��ǰ�� ǰ�� ��ǰ�Դϴ�.');
			} else {
				if(Number(document.form1.quantity.value)>0) {
					if(Number(thisValueSplit[1]) < Number(document.form1.quantity.value)) {
						alert('���� ��ǰ�� ����� �����մϴ�.');
					} else {
						setTotalPrice(document.form1.quantity.value);
						if(thisValueSplit.length>3 && thisValueSplit[4].length>0 && document.getElementById("acimage"+idxValue)) {
							document.getElementById("acimage"+idxValue).src="<?=$Dir.DataDir."shopimages/product/"?>"+thisValueSplit[4];
						} else {
							document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
						}
						return;
					}
				} else {
					alert('�� ��ǰ ������ �Է��� �ּ���.');
				}
			}
		} else {
			setTotalPrice(document.form1.quantity.value);
			if(thisValueSplit.length>3 && thisValueSplit[4].length>0 && document.getElementById("acimage"+idxValue)) {
				document.getElementById("acimage"+idxValue).src="<?=$Dir.DataDir."shopimages/product/"?>"+thisValueSplit[4];
			} else {
				document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
			}
			return;
		}

		thisObj.options[currentSelectIndex].selected = true;
	} else {
		setTotalPrice(document.form1.quantity.value);
		document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
		return;
	}
}

function getQuantityCheck(tmp) {
	var i=true;
	var j=1;
	while(i) {
		if(document.getElementById("acassemble"+j)) {
			if(document.getElementById("acassemble"+j).value) {
				arracassemble = document.getElementById("acassemble"+j).value.split("|");
				if(arracassemble[1].length>0 && Number(tmp) > Number(arracassemble[1])) {
					return false;
				}
			}
		} else {
			i=false;
		}
		j++;
	}
	return true;
}

function assemble_proinfo(idxValue) { // ������ǰ ���� ��ǰ ��������
	if(document.getElementById("acassemble"+idxValue)) {
		if(document.getElementById("acassemble"+idxValue).value.length>0) {
			thisValueSplit = document.getElementById("acassemble"+idxValue).value.split('|');
			if(thisValueSplit[0].length>0) {
				product_info_pop("assemble_proinfo.php?op=<?=$productcode?>&np="+thisValueSplit[0],"assemble_proinfo_"+thisValueSplit[0],700,700,"yes");
			} else {
				alert("�ش� ��ǰ������ �������� �ʽ��ϴ�.");
			}
		}
	}
}

function product_info_pop(url,win_name,w,h,use_scroll) {
	var x = (screen.width - w) / 2;
	var y = (screen.height - h) / 2;
	if (use_scroll==null) use_scroll = "no";
	var use_option = "";
	use_option = use_option + "toolbar=no, channelmode=no, location=no, directories=no, resizable=no, menubar=no";
	use_option = use_option + ", scrollbars=" + use_scroll + ", left=" + x + ", top=" + y + ", width=" + w + ", height=" + h;

	var win = window.open(url,win_name,use_option);
	return win;
}
<? } ?>

var productUrl = "http://<?=$_data->shopurl?>?prdt=<?=$productcode?>";
var productName = "<?=strip_tags($_pdata->productname)?>";
function goFaceBook()
{
	var href = "http://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(productUrl) + "&t=" + encodeURIComponent(productName);
	var a = window.open(href, 'Facebook', '');
	if (a) {
		a.focus();
	}
}

function goTwitter()
{
	var href = "http://twitter.com/share?text=" + encodeURIComponent(productName) + " " + encodeURIComponent(productUrl);
	var a = window.open(href, 'Twitter', '');
	if (a) {
		a.focus();
	}
}

function goMe2Day()
{
	var href = "http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent(productName) + " " + encodeURIComponent(productUrl) + "&new_post[tags]=" + encodeURIComponent('<?=$_data->shopname?>');
	var a = window.open(href, 'Me2Day', '');
	if (a) {
		a.focus();
	}
}

function snsSendCheck(type){
<?if($arSnsType[0] != "N"){?>
	if(confirm("�������� �������� �α����� �ʿ��մϴ�. �α����Ͻðڽ��ϱ�?")){
		document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
	}else{
<?}?>
		if(type =="t")
			goTwitter();
		else if(type =="f")
			goFaceBook();
		else if(type =="m")
			goMe2Day();
<?if($arSnsType[0] != "N") {?>
	}
<?}?>
}
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td><? include dirname(__FILE__).'/skin/detail.php';	?></td>
</tr>
<form name=couponform method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode value="">
<input type=hidden name=coupon_code value="">
<input type=hidden name=productcode value="<?=$productcode?>">
<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>
</form>
<form name=idxform method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=productcode value="<?=$productcode?>">
<input type=hidden name=sort value="<?=$sort?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<input type=hidden name=qnablock value="<?=$qnablock?>">
<input type=hidden name=qnagotopage value="<?=$qnagotopage?>">
<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>
</form>
<form name=wishform method=post action="<?=$Dir.FrontDir?>confirm_wishlist.php" target="confirmwishlist">
<input type=hidden name=productcode value="<?=$productcode?>">
<input type=hidden name=opts>
<input type=hidden name=option1>
<input type=hidden name=option2>
</form>

<? if($_pdata->vender>0){?>
<form name=custregminiform method=post>
<input type=hidden name=sellvidx value="<?=$_vdata->vender?>">
<input type=hidden name=memberlogin value="<?=(strlen($_ShopInfo->getMemid())>0?"Y":"N")?>">
</form>
<? }?>
</table>
<? if($_data->sns_ok == "Y" && ($_pdata->sns_state == "Y" || $_pdata->gonggu_product == "Y")){?>
<script type="text/javascript" src="<?=$Dir?>lib/sns.js"></script>
<script type="text/javascript">
<!--
var pcode = "<?=$productcode ?>";
var memId = "<?=$_ShopInfo->getMemid() ?>";
var fbPicture ="<?=$fbThumb?>";
var preShowID ="";
var snsCmt = "";
var snsLink = "";
var snsType = "";
var gRegFrm = "";

$j(document).ready( function () {
	if(memId != ""){
		snsImg();
		snsInfo();
	}
	showSnsComment();
	showGongguCmt();
});
//-->
</script>
<? include ($Dir.FrontDir."snsGongguToCmt.php") ?>
<?}?>
<div id="create_openwin" style="display:none"></div>
<? include ($Dir."lib/bottom.php") ?>

<?=$onload?>
<script language="JavaScript">
<!--
	function _orderNaverCheckout() {
		if(document.form1.quantity.value.length==0 || document.form1.quantity.value==0) {
			alert("�ֹ������� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		}
		if(!IsNumeric(document.form1.quantity.value)) {
			alert("�ֹ������� ���ڸ� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		}
		if(miniq>1 && document.form1.quantity.value<=1) {
			alert("�ش� ��ǰ�� ���ż����� "+miniq+"�� �̻� �ֹ��� �����մϴ�.");
			document.form1.quantity.focus();
			return;
		}

		if("<?=$opti?>" != "") {
			document.form1.opts.value="";
			try {
				for(i=0;i<"<?=$opti?>";i++) {
					if(document.form1.optselect[i].value==1 && document.form1.mulopt[i].selectedIndex==0) {
						alert('�ʼ����� �׸��Դϴ�. �ɼ��� �ݵ�� �����ϼ���');
						document.form1.mulopt[i].focus();
						return;
					}
					document.form1.opts.value+=document.form1.mulopt[i].selectedIndex+",";
				}
			} catch (e) {}
		}

		if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex<2) {
			alert('�ش� ��ǰ�� �ɼ��� �����ϼ���.');
			document.form1.option1.focus();
			return;
		}
		if(typeof(document.form1.option2)!="undefined" && document.form1.option2.selectedIndex<2) {
			alert('�ش� ��ǰ�� �ɼ��� �����ϼ���.');
			document.form1.option2.focus();
			return;
		}
		if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex>=2) {
			temp2=document.form1.option1.selectedIndex-1;
			if(typeof(document.form1.option2)=="undefined") temp3=1;
			else temp3=document.form1.option2.selectedIndex-1;
			if(num[(temp3-1)*20+(temp2-1)]==0) {
				alert('�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� �ɼ��� �����ϼ���');
				document.form1.option1.focus();
				return;
			}
		}
		if(typeof(document.form1.package_type)!="undefined" && typeof(document.form1.packagenum)!="undefined" && document.form1.package_type.value=="Y" && document.form1.packagenum.selectedIndex<2) {
			alert('�ش� ��ǰ�� ��Ű���� �����ϼ���.');
			document.form1.packagenum.focus();
			return;
		}

		<? if($_pdata->assembleuse=="Y") { ?>
		if(typeof(document.form1.assemble_type)=="undefined") {
			alert('���� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. ���Ű� �Ұ����մϴ�.');
			return;
		} else {
			if(document.form1.assemble_type.value.length>0) {
				arracassembletype = document.form1.assemble_type.value.split("|");
				document.form1.assemble_list.value="";

				for(var i=1; i<=arracassembletype.length; i++) {
					if(arracassembletype[i]=="Y") {
						if(document.getElementById("acassemble"+i).options.length<2) {
							alert('�ʼ� ������ǰ�� ��ǰ�� ��� ���Ű� �Ұ����մϴ�.');
							document.getElementById("acassemble"+i).focus();
							return;
						} else if(document.getElementById("acassemble"+i).value.length==0) {
							alert('�ʼ� ������ǰ�� ������ �ּ���.');
							document.getElementById("acassemble"+i).focus();
							return;
						}
					}

					if(document.getElementById("acassemble"+i)) {
						if(document.getElementById("acassemble"+i).value.length>0) {
							arracassemblelist = document.getElementById("acassemble"+i).value.split("|");
							document.form1.assemble_list.value += "|"+arracassemblelist[0];
						} else {
							document.form1.assemble_list.value += "|";
						}
					}
				}
			} else {
				alert('���� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. ���Ű� �Ұ����մϴ�.');
				return;
			}
		}
		<? } ?>

		var param = "";
		param += "?goodsId=<?=$_pdata->productcode?>";
		param += "&goodsName=<?=$_pdata->productname?>";
		param += "&goodsPrice=<?=$_pdata->sellprice?>";
		param += "&goodsCount=" + document.getElementById("quantity").value;
		param += "&isTransMoney=1";
		param += "&goodsTransType=0";
		param += "&limitGoodsTransMoney=<?=$_data->deli_miniprice?>";
		param += "&goodsTransMoney=<?=$_data->deli_basefee?>";

		var goodsOption = "";
<?
	if (strlen($optcode) > 0) {
		foreach ($optionadd as $key => $value) {
			if ($value) $newOptionadd[] = $value;
		}

		$i = 0;
		foreach ($optionadd as $key => $value) {
			if ($value) {
				$arrOption = explode('', $value);
?>
			goodsOption += "<?=$arrOption[0]?>:" + document.form1.mulopt[<?=$key?>].value;
<? if ($i < count($newOptionadd) - 1) { ?>
			goodsOption += "/";
<? } ?>
<?
				$i++;
			}

		}

	} else {
?>
		if (document.getElementById("option1").innerText != '') {
<? $optionName1 = explode(',', $_pdata->option1);	?>
			goodsOption += "<?=$optionName1[0]?>:" + document.getElementById("option1").value;
		}
		if (document.getElementById("option2").innerText != '') {
<? $optionName2 = explode(',', $_pdata->option2);	?>
			goodsOption += "/";
			goodsOption += "<?=$optionName2[0]?>:" + document.getElementById("option2").value;
		}
<?
	}
?>

		param += "&goodsOption=" + encodeURIComponent(goodsOption);

		location.href = "/_NaverCheckout/order.php" + param;

	}

	function _wishlistNaverCheckout() {
		var isGoodsImage = 1;
		var isGoodsThumbImage = 1;
		var goodsImage = "<?=$_pdata->maximage?>";
		var goodsThumbImage = "<?=$_pdata->tinyimage?>";

		if (!goodsImage) {
			isGoodsImage = 0;
			goodsImage = "";
		}
		if (!goodsThumbImage) {
			isGoodsThumbImage = 0;
			goodsThumbImage = "";
		}

		var param = "";
		param += "?goodsId=<?=$_pdata->productcode?>";
		param += "&goodsName=<?=$_pdata->productname?>";
		param += "&goodsPrice=<?=$_pdata->sellprice?>";
		param += "&isGoodsImage=" + isGoodsImage;
		param += "&goodsImage=" + goodsImage;
		param += "&isGoodsThumbImage=" + isGoodsThumbImage;
		param += "&goodsThumbImage=" + goodsThumbImage;

		//alert(param);

		window.open("/_NaverCheckout/wishlist.php" + param, "_wishlistNaverCheckout", "width=397, height=304, scrollbars=yes");
	}
//-->
</script>
</BODY>
</HTML>