<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
@include_once($Dir."lib/venderlib.php");
include_once($Dir."lib/shopdata.php");

$engine_num = "plusmall"; //���������� ��ȣ
function ReadEngine($file) {
	$filename = DirPath.DataDir."shopimages/etc/".$file;

	if(file_exists($filename)==true) {
		if($fp=@fopen($filename, "r")) {
			$szdata=fread($fp, filesize($filename));
			fclose($fp);
			$engine=unserialize($szdata);
		}
	}
	return $engine;
}
$engineval = ReadEngine("engineinfo.db");

if($engineval[$engine_num] == "checked") {
	$sql = "SELECT COUNT(*) AS allcount FROM tblproduct ";
	$sql.= "WHERE display='Y' ";
	$sql.= "AND (quantity IS NULL OR quantity > 0) ";
	$tresult=mysql_query($sql,get_db_conn());
	$rowcnt=mysql_fetch_object($tresult);
	$total = $rowcnt->allcount;
	mysql_free_result($tresult);

	$sql = "SELECT productcode, productname, sellprice, quantity, production, ";
	$sql.= "deli, deli_price, vender, tinyimage, minimage, maximage, madein, reserve, reservetype, regdate, opendate FROM tblproduct ";
	$sql.= "WHERE display='Y' ";
	$sql.= "AND (quantity IS NULL OR quantity > 0) ";
	$presult=mysql_query($sql,get_db_conn());

	if($total>0)
	{
		$shopidname = str_replace(" ", "", preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", strip_tags($_data->shopname))).str_replace(".","",str_replace("www.","",getenv("HTTP_HOST")));
		echo "
<html>
<head>
<title>��ǰ��� �˻�����������</title>
<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>
<meta http-equiv=\"Expires\" content=\"0\"/>
<meta http-equiv=\"Pragma\" content=\"no-cache\"/>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=euc-kr\">
</head>
<body bgcolor=\"#FFFFFF\">

��ü��ǰ : ".$total." ���Դϴ�. 
<table width=\"98%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" bordercolorlight=\"#7CA482\" bordercolordark=\"#FFFFFF\">
<tr bgcolor=\"#E5EFE7\">
<td height=\"20\" width=\"50\"align=\"center\">���θ���</td>
<td>�Һз�</td>
<td>��ǰ��</td>
<td>�ǸŰ�</td>
<td>�̹���URL</td>
<td>��ǰ����SPEC</td>
</tr>\n";

		$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
		$sql.= "WHERE type LIKE 'L%'";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$code_name[$row->codeA.$row->codeB.$row->codeC.$row->codeD] = $row->code_name;
		}
		mysql_free_result($result);

		if(getVenderUsed()==true) {
			$sql = "SELECT vender, deli_price, deli_mini FROM tblvenderinfo ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
					$vender_deli_price[$row->vender] = $row->deli_price;
					$vender_deli_mini[$row->vender] = $row->deli_mini;
			}
			mysql_free_result($result);
		}
		
		//echo $_data->shoptitle."�Դϴ�.��(".(int)$total.")�� �Դϴ�.\n";
		//echo "<total>".(int)$total."\n<update>".date("YmdHis")."\n";
		//echo "<<<total>>>\n\t<<<�ѻ�ǰ��>>>".(int)$total."\n\t<<<����������>>>".date("YmdHis")."\n\t<<<����/�߰���ǰ��>>>".(int)$total."\n<<</total>>>\n\n";
		
		$i=1;
		while($row=mysql_fetch_object($presult)) {
			$codeA=substr($row->productcode,0,3); //1�� CODE
			$codeB=substr($row->productcode,3,3); //2�� CODE
			$codeC=substr($row->productcode,6,3); //3�� CODE
			$codeD=substr($row->productcode,9,3); //4�� CODE
			
			$part1="";
			$part2="";
			$part3="";
			$part4="";
			if($codeA>0) {
				$part1 	  	= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", strip_tags($code_name[$codeA."000000000"]));
				if($codeB>0) {
					$part2 	  	= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", strip_tags($code_name[$codeA.$codeB."000000"]));
					if($codeC>0) {
						$part3 	  	= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", strip_tags($code_name[$codeA.$codeB.$codeC."000"]));
						if($codeD>0) {
							$part4 	  	= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", strip_tags($code_name[$codeA.$codeB.$codeC.$codeD]));
						}
					}
				}
			}

			$itemcode 		= $row->productcode;
			$modelname		= strip_tags($row->productname);
			$modelname		= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $modelname);
			$price			= $row->sellprice;
			$productcom		= strip_tags($row->production);
			$productcom		= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $productcom);
			$url			= "http://".$shopurl."?productcode=".$row->productcode;
			$coupon			= "";
			$GoodsImg_Url	= "http://".$shopurl.DataDir."shopimages/product/".$row->tinyimage;
			$GoodsImg_Url1	= "http://".$shopurl.DataDir."shopimages/product/".$row->minimage;
			$GoodsImg_Url2	= "http://".$shopurl.DataDir."shopimages/product/".$row->maximage;
			$madein			= strip_tags($row->madein);
			$madein			= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $madein);
			$reserve		= getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
			$regdate		= str_replace("-", "", substr($row->regdate,0,10));
			$opendate		= $row->opendate;
			
			if(strlen($part4)>0) {
				$part 	  	= trim($part4);
			} else {
				if(strlen($part3)>0) {
					$part 	  	= trim($part3);
				} else {
					if(strlen($part2)>0) {
						$part 	  	= trim($part2);
					} else {
						$part 	  	= trim($part1);
					}
				}
			}
			
			$BesongBi		= "0";
			if($row->deli=="N" && $row->deli_price == "0")
			{
				if($row->vender>0) {
					if($vender_deli_price[$row->vender]>0 && $row->sellprice<$vender_deli_mini[$row->vender]) {
						$BesongBi	= $vender_deli_price[$row->vender];
					}
				} else {
					if($_data->deli_basefee>0 && $row->sellprice<$_data->deli_miniprice) {
						$BesongBi	= $_data->deli_basefee;
					}
				}
			} else if($row->deli_price>0) {
				$BesongBi	= $row->deli_price;
			}
			echo "\t<tr>
<td><a href=\"http://".$shopurl."\">".$shopidname."</a></td>
<td>".$part."</td>
<td><a href=\"".trim($url)."\">".trim($modelname)."</a></td>
<td>".trim($price)."</td>
<td>".trim($GoodsImg_Url2)."</td>	
<td></td>
</tr>\n";
		$i++;
		}
		mysql_free_result($presult);
	echo "\t</table>

</body>
</html>
	";
	}
} else {
	echo "<html><head><title></title></head><body onload=\"alert('���� �������� �̻�� ������ �Դϴ�.');window.close();\"></body></html>";exit;
}
?>