<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 10;

$sort=$_REQUEST["sort"];
$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}
////////////////////////

/*
$_POST
[mode] => copy
[code] => 002001000000
[cproductcodes] => 002001000000000001|002001000000000002|002001000000000003|002001000000000004|
[block] =>
[gotopage] =>
[searchtype] => 0
[keyword] =>
[sort] =>
[cproductcode] => 002001000000000004
[copycode] => 009000000000
[oldcode] => 002001000000
[copycode_name] => �м��ڵ��ǰ
*/

$mode=$_POST["mode"];
$code=$_POST["code"];
$keyword=$_POST["keyword"];
$searchtype=$_POST["searchtype"];
$copycode=$_POST["copycode"];
if(strlen($searchtype)==0) $searchtype=0;

$cproductcodes=$_POST["cproductcodes"];

if ($mode=="copy" || $mode=="move") {

	//exit(print_r($_POST));

	$cproductcodes=substr($cproductcodes,0,-1);
	$cproductcode=explode("|",$cproductcodes);
	$size = sizeof($cproductcode);

	if ($size>100) {
		echo "<script>alert('�ѹ��� 100������ �ٲٽ� �� �ֽ��ϴ�.');history.go(-1);</script>";
		exit;
	}
	if ($size==0) {
		echo "<script>alert('ī�װ� �̵�/������ ��ǰ�� �����ϼ���.');history.go(-1);</script>";
		exit;
	}

	$sql = "SELECT type FROM tblproductcode WHERE codeA='".substr($copycode,0,3)."' ";
	$sql.= "AND codeB='".substr($copycode,3,3)."' ";
	$sql.= "AND codeC='".substr($copycode,6,3)."' AND codeD='".substr($copycode,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row || !ereg("X",$row->type)) {
		echo "<script>alert('��ǰī�װ� ������ �߸��Ǿ����ϴ�.');history.go(-1);</script>";
		exit;
	}

	$copycount=0;
	$vender_prcodelist=array();
	for ($i=0;$i<=$size;$i++) {
		if (strlen($cproductcode[$i])==18) {
			$sql = "SELECT * FROM tblproduct WHERE productcode = '".$cproductcode[$i]."'";
			$result = mysql_query($sql,get_db_conn());
			if ($row=mysql_fetch_object($result)) {

				$sql = "SELECT productcode FROM tblproduct WHERE productcode LIKE '".$copycode."%' ";
				$sql.= "ORDER BY productcode DESC LIMIT 1 ";
				$result = mysql_query($sql,get_db_conn());
				if ($rows = mysql_fetch_object($result)) {
					$newproductcode = substr($rows->productcode,12)+1;
					$newproductcode = substr("000000".$newproductcode,strlen($newproductcode));
				} else {
					$newproductcode = "000001";
				}
				mysql_free_result($result);

				$path = $Dir.DataDir."shopimages/product/";
				$widepath = $Dir.DataDir."shopimages/wideimage/";
				if (strlen($row->maximage)>0) {
					$maximage=$copycode.$newproductcode.".".strtolower(substr($row->maximage,strlen($row->maximage)-3,3));
					if (file_exists("$path$row->maximage")==true) {
						if ($mode=="move") rename("$path$row->maximage","$path$maximage");
						else copy("$path$row->maximage","$path$maximage");
					}
				} else $maximage="";
				if (strlen($row->minimage)>0) {
					$minimage=$copycode.$newproductcode."2.".strtolower(substr($row->minimage,strlen($row->minimage)-3,3));
					if (file_exists("$path$row->minimage")==true) {
						if ($mode=="move") rename("$path$row->minimage","$path$minimage");
						else copy("$path$row->minimage","$path$minimage");
					}
				} else $minimage="";
				if (strlen($row->tinyimage)>0) {
					$tinyimage=$copycode.$newproductcode."3.".strtolower(substr($row->tinyimage,strlen($row->tinyimage)-3,3));
					if (file_exists("$path$row->tinyimage")==true) {
						if ($mode=="move") rename("$path$row->tinyimage","$path$tinyimage");
						else copy("$path$row->tinyimage","$path$tinyimage");
					}
				} else $tinyimage="";

				if (strlen($row->wideimage)>0) {
					$wideimage=$copycode.$newproductcode.strtolower(substr($row->wideimage,strlen($row->wideimage)-3,3));
					if (file_exists("$widepath$row->wideimage")==true) {
						if ($mode=="move") rename("$widepath$row->wideimage","$widepath$wideimage");
						else copy("$widepath$row->wideimage","$widepath$wideimage");
					}
				} else $wideimage="";
				if (strlen($row->quantity)==0) $quantity="NULL";
				else $quantity=$row->quantity;

				$productname = mysql_escape_string($row->productname);
				$production = mysql_escape_string($row->production);
				$madein = mysql_escape_string($row->madein);
				$model = mysql_escape_string($row->model);
				$tempkeyword = mysql_escape_string($row->keyword);
				$addcode = mysql_escape_string($row->addcode);
				$userspec = mysql_escape_string($row->userspec);
				$option1 = mysql_escape_string($row->option1);
				$option2 = mysql_escape_string($row->option2);
				$content = mysql_escape_string($row->content);
				$selfcode = mysql_escape_string($row->selfcode);
				$assembleproduct = mysql_escape_string($row->assembleproduct);

				$sql = "INSERT tblproduct SET ";
				$sql.= "productcode		= '".$copycode.$newproductcode."', ";
				$sql.= "productname		= '".$productname."', ";
				$sql.= "assembleuse		= '".$row->assembleuse."', ";
				$sql.= "assembleproduct	= '".$row->assembleproduct."', ";
				$sql.= "sellprice		= ".$row->sellprice.", ";
				$sql.= "consumerprice	= ".$row->consumerprice.", ";
				$sql.= "buyprice		= ".$row->buyprice.", ";
				$sql.= "reserve			= '".$row->reserve."', ";
				$sql.= "reservetype		= '".$row->reservetype."', ";
				$sql.= "production		= '".$production."', ";
				$sql.= "madein			= '".$madein."', ";
				$sql.= "model			= '".$model."', ";
				$sql.= "brand			= '".$row->brand."', ";
				$sql.= "opendate		= '".$row->opendate."', ";
				$sql.= "selfcode		= '".$row->selfcode."', ";
				$sql.= "bisinesscode	= '".$row->bisinesscode."', ";
				$sql.= "quantity		= ".$quantity.", ";
				$sql.= "group_check		= '".$row->group_check."', ";
				$sql.= "keyword			= '".$tempkeyword."', ";
				$sql.= "addcode			= '".$addcode."', ";
				$sql.= "userspec		= '".$userspec."', ";
				$sql.= "maximage		= '".$maximage."', ";
				$sql.= "minimage		= '".$minimage."', ";
				$sql.= "tinyimage		= '".$tinyimage."', ";
				$sql.= "wideimage		= '".$wideimage."', ";
				$sql.= "option_price	= '".$row->option_price."', ";
				$sql.= "option_quantity	= '".$row->option_quantity."', ";
				$sql.= "option1			= '".$option1."', ";
				$sql.= "option2			= '".$option2."', ";
				$sql.= "etctype			= '".$row->etctype."', ";
				$sql.= "deli			= '".$row->deli."', ";
				$sql.= "package_num		= '".(int)$row->package_num."', ";
				$sql.= "display			= '".$row->display."', ";
				if ($newtime=="Y")
					$sql.= "date		= '".date("YmdHis")."', ";
				else
					$sql.= "date		= '".$row->date."', ";
				$sql.= "vender			= '".$row->vender."', ";
				$sql.= "rental			= '".$row->rental."', ";
				$sql.= "tax_yn			= '".$row->tax_yn."', ";
				$sql.= "regdate			= now(), ";
				$sql.= "modifydate		= now(), ";
				$sql.= "content			= '".$content."' ";
				$insert = mysql_query($sql,get_db_conn());
				$insert_pridx = mysql_insert_id();

				//jdy �߰� ���� ��ȸ �̵�
				copyCommission($cproductcode[$i], $copycode.$newproductcode);

				// ��Ƽ ī�װ� ����
				if ($mode=="move") {
					// �̵��Ǵ� ��Ƽī�װ� ����
					$multiCateSQL = "
						UPDATE
							`tblcategorycode`
						SET
							`categorycode`='".$copycode."' ,
							`productcode` = '".$copycode.$newproductcode."'
						WHERE
							`categorycode`= '".$code."'
							AND
							`productcode` = '".$cproductcode[$i]."'
						LIMIT 1 ;
					";
					@mysql_query($multiCateSQL,get_db_conn());

					// �̵��Ǵ� ��Ƽī�װ��� �߰� ī�װ� ����
					$multiCateSQL = "
						UPDATE
							`tblcategorycode`
						SET
							`productcode` = '".$copycode.$newproductcode."'
						WHERE
							`productcode` = '".$cproductcode[$i]."'
						;
					";
					@mysql_query($multiCateSQL,get_db_conn());
				}

				if ($mode=="copy") {
					// ����Ǵ� ��Ƽī�װ� ����
					$multiCateSQL = "
						INSERT
							`tblcategorycode`
						SET
							`categorycode`='".$copycode."' ,
							`productcode` = '".$copycode.$newproductcode."'
					";
					@mysql_query($multiCateSQL,get_db_conn());
				}







				/// ���� ��� �̵� ���� �߰� �κ�
				$sql_gosi = "insert into tblproduct_detail select ".$insert_pridx.",didx,dtitle,dcontent FROM `tblproduct_detail` WHERE pridx ='".$row->pridx."'";
				@mysql_query($sql_gosi,get_db_conn());


				$fromproductcodes.="|".$cproductcode[$i];
				$copyproductcodes.="|".$copycode.$newproductcode;

				if($row->vender>0) {
					$vender_prcodelist[$row->vender]["IN"][]=$copycode.$newproductcode;
				}

				if ($mode=="move") {
					if($row->vender>0) {
						$vender_prcodelist[$row->vender]["OUT"][]=$row->productcode;
					}


					/// ���� ��� �̵� ���� �߰� �κ�
					$sql_gosi = "delete FROM `tblproduct_detail` WHERE pridx ='".$row->pridx."'";
					@mysql_query($sql_gosi,get_db_conn());

					$sql = "DELETE FROM tblproduct WHERE productcode = '".$cproductcode[$i]."' ";
					mysql_query($sql,get_db_conn());

					#�±װ��� �����
					$sql = "DELETE FROM tbltagproduct WHERE productcode = '".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					//jdy �߰� ���� ��ȸ �����
					$sql = "DELETE FROM product_commission WHERE productcode = '".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblproductgroupcode SET productcode = '".$copycode.$newproductcode."' ";
					$sql.= "WHERE productcode='".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblproductreview SET productcode = '".$copycode.$newproductcode."' ";
					$sql.= "WHERE productcode='".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblproducttheme SET productcode = '".$copycode.$newproductcode."' ";
					$sql.= "WHERE productcode='".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblcollection SET productcode = '".$copycode.$newproductcode."' ";
					$sql.= "WHERE productcode='".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblwishlist SET productcode = '".$copycode.$newproductcode."' ";
					$sql.= "WHERE productcode='".$cproductcode[$i]."'";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblcollection SET ";
					$sql.= "collection_list = replace(collection_list,'".$cproductcode[$i]."','".$copycode.$newproductcode."') ";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblspecialcode SET ";
					$sql.= "special_list = replace(special_list,'".$cproductcode[$i]."','".$copycode.$newproductcode."') ";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblspecialmain SET ";
					$sql.= "special_list = replace(special_list,'".$cproductcode[$i]."','".$copycode.$newproductcode."') ";
					mysql_query($sql,get_db_conn());

					if($row->assembleuse=="Y") { //�ڵ�/���� ��ǰ�� ���
						$sql = "UPDATE tblassembleproduct SET productcode = '".$copycode.$newproductcode."' ";
						$sql.= "WHERE productcode='".$cproductcode[$i]."'";
						mysql_query($sql,get_db_conn());

						$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
						$sql.= "WHERE productcode = '".$copycode.$newproductcode."' ";
						$result = mysql_query($sql,get_db_conn());
						if($row = @mysql_fetch_object($result)) {
							if(strlen(str_replace("","",$row->assemble_pridx))>0) {
								$sql = "UPDATE tblproduct SET ";
								$sql.= "assembleproduct = REPLACE(assembleproduct,',".$cproductcode[$i]."',',".$copycode.$newproductcode."') ";
								$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
								$sql.= "AND assembleuse != 'Y' ";
								mysql_query($sql,get_db_conn());
							}
						}
						mysql_free_result($result);
					} else {
						$sql = "UPDATE tblassembleproduct SET ";
						$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$row->pridx."','".$insert_pridx."'), ";
						$sql.= "assemble_list=REPLACE(assemble_list,',".$row->pridx."',',".$insert_pridx."') ";
						mysql_query($sql,get_db_conn());
					}

					$log_content = "## ��ǰ�̵��Է� ## - ��ǰ�ڵ� ".$cproductcode[$i]." => ".$copycode.$newproductcode." - ��ǰ�� : ".$productname;
					ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
				} else {
					if($row->group_check=="Y") {
						$sql = "INSERT INTO tblproductgroupcode SELECT '".$copycode.$newproductcode."', group_code FROM tblproductgroupcode WHERE productcode = '".$cproductcode[$i]."' ";
						mysql_query($sql,get_db_conn());
					}
					if($row->assembleuse=="Y") { //�ڵ�/���� ��ǰ�� ���
						$sql = "INSERT INTO tblassembleproduct ";
						$sql.= "SELECT '".$copycode.$newproductcode."', assemble_type, assemble_title, assemble_pridx, assemble_list FROM tblassembleproduct ";
						$sql.= "WHERE productcode='".$cproductcode[$i]."' ";
						mysql_query($sql,get_db_conn());

						$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
						$sql.= "WHERE productcode = '".$cproductcode[$i]."' ";
						$result = mysql_query($sql,get_db_conn());
						if($row = @mysql_fetch_object($result)) {
							if(strlen(str_replace("","",$row->assemble_pridx))>0) {
								$sql = "UPDATE tblproduct SET ";
								$sql.= "assembleproduct = CONCAT(assembleproduct,',".$copycode.$newproductcode."') ";
								$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
								$sql.= "AND assembleuse != 'Y' ";
								mysql_query($sql,get_db_conn());
							}
						}
						mysql_free_result($result);
					} else {
						$sql = "UPDATE tblproduct SET assembleproduct = '' ";
						$sql.= "WHERE productcode='".$copycode.$newproductcode."'";
						mysql_query($sql,get_db_conn());
					}

					$log_content = "## ��ǰ�����Է� ## - ��ǰ�ڵ� ".$cproductcode[$i]." => ".$copycode.$newproductcode." - ��ǰ�� : ".$productname;
					ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
				}
				$copycount++;
			}
		}
	}
	if ($copycount!=0) {
		//������ü ��ǰ ���� ó��
		if(count($vender_prcodelist)>0) {
			$tmpvender=$vender_prcodelist;
			while(list($vender,$prarr)=each($tmpvender)) {
				unset($tmpcodeA);
				for($kk=0;$kk<count($prarr["IN"]);$kk++) {
					//insert ó��
					setVenderDesignInsert($vender, $prarr["IN"][$kk]);

					if(strlen($prarr["OUT"][$kk])==18) {
						//move ó��
						$tmpcodeA[substr($prarr["OUT"][$kk],0,3)]=true;
						setVenderThemeSpecialUpdate($vender, $prarr["IN"][$kk], $prarr["OUT"][$kk]);
					}
				}
				//�̴ϼ� ��ǰ�� ������Ʈ (������ ��ǰ��)
				$sql="SELECT COUNT(*) as prdt_allcnt,COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
				$sql.="WHERE vender='".$vender."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				$prdt_allcnt=(int)$row->prdt_allcnt;
				$prdt_cnt=(int)$row->prdt_cnt;
				mysql_free_result($result);

				setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $vender);

				if(count($tmpcodeA)>0) {
					$sql = "SELECT SUBSTRING(productcode,1,3) as codeA FROM tblproduct ";
					$sql.= "WHERE ( ";
					$arr_codeA=$tmpcodeA;
					$i=0;
					while(list($key,$val)=each($arr_codeA)) {
						if(strlen($key)==3) {
							if($i>0) $sql.= "OR ";
							$sql.= "productcode LIKE '".$key."%' ";
							$i++;
						}
					}
					$sql.= ") ";
					$sql.= "AND vender='".$vender."' ";
					$sql.= "GROUP BY codeA ";
					$result=mysql_query($sql,get_db_conn());
					while($row=mysql_fetch_object($result)) {
						unset($tmpcodeA[$row->codeA]);
					}
					mysql_free_result($result);

					if(count($tmpcodeA)>0) {
						$str_codeA="";
						while(list($key,$val)=each($tmpcodeA)) {
							$str_codeA.=$key.",";

							$imagename = $Dir.DataDir."shopimages/vender/".$vender."_CODE10_".$key.".gif";
							@unlink($imagename);
						}
						$str_codeA=substr($str_codeA,0,-1);
						$str_codeA=ereg_replace(',','\',\'',$str_codeA);
						setVenderDesignDelete($str_codeA, $vender);
					}
				}
			}
		}

		delProductMultiImg($mode,substr($fromproductcodes,1),substr($copyproductcodes,1));

		if ($mode=="move") $onload="<script>alert('$copycount ���� �����Ͱ� [".ereg_replace("\"","",$copycode_name)."]���� �̵��Ǿ����ϴ�.');</script>";
		else $onload="<script>alert('".$copycount." ���� �����Ͱ� [".ereg_replace("\"","",$copycode_name)."]���� ����Ǿ����ϴ�.');</script>";
	}

} else if ($mode=="delete") {
	$cproductcodes=substr($cproductcodes,0,-1);
	$allprcode=$cproductcodes;

	$cproductcode=explode("|",$cproductcodes);
	$size = sizeof($cproductcode);

	if ($size>100) {
		echo "<script>alert('�ѹ��� 100������ �����Ͻ� �� �ֽ��ϴ�.');history.go(-1);</script>";
		exit;
	}
	if ($size==0) {
		echo "<script>alert('������ ��ǰ�� �����ϼ���.');history.go(-1);</script>";
		exit;
	}

	$prcodelist = ereg_replace("\|","','",$allprcode);
	$arrvender=array();
	$arrvenderid=array();
	$arrpridx=array();
	$arrassembleuse=array();
	$arrassembleproduct=array();
	$arrproductcode=array();
	$sql = "SELECT vender,pridx,assembleuse,assembleproduct,productcode FROM tblproduct ";
	$sql.= "WHERE productcode IN ('".$prcodelist."') ";
	$p_result=mysql_query($sql,get_db_conn());
	while($p_row=mysql_fetch_object($p_result)) {
		if($p_row->vender>0 && strlen($arrvenderid[$p_row->vender])==0) {
			$arrvender[]=$p_row->vender;
			$arrvenderid[$p_row->vender]=$p_row->vender;
		}
		$arrpridx[]=$p_row->pridx;
		$arrassembleuse[]=$p_row->assembleuse;
		$arrassembleproduct[]=$p_row->assembleproduct;
		$arrproductcode[]=$p_row->productcode;
	}
	mysql_free_result($p_result);

	$sql = "DELETE FROM tblproduct WHERE productcode IN ('".$prcodelist."')";
	mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblproductgroupcode WHERE productcode IN ('".$prcodelist."')";
	$result = mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblproducttheme WHERE productcode IN ('".$prcodelist."')";
	$result = mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblproductreview WHERE productcode IN ('".$prcodelist."')";
	mysql_query($sql,get_db_conn());

	#�±װ��� �����
	$sql = "DELETE FROM tbltagproduct WHERE productcode IN ('".$prcodelist."')";
	mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblwishlist WHERE productcode IN ('".$prcodelist."')";
	mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblcollection WHERE productcode IN ('".$prcodelist."')";
	mysql_query($sql,get_db_conn());

	// ��Ƽī�װ� ����
	$sql = "DELETE FROM `tblcategorycode` WHERE `productcode` IN ('".$prcodelist."')";
	@mysql_query($sql,get_db_conn());

	//jdy �߰� ���� ��ȸ �����
	$sql = "DELETE FROM product_commission WHERE productcode = '".$prcodelist."'";
	mysql_query($sql,get_db_conn());

	for($vz=0; $vz<count($arrpridx); $vz++) { // �ڵ�/���� �⺻������ǰ�� ���� ó��
		if($arrassembleuse[$vz]=="Y") {
			$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
			$sql.= "WHERE productcode = '".$arrproductcode[$vz]."' ";
			$result = mysql_query($sql,get_db_conn());
			if($row = @mysql_fetch_object($result)) {
				$sql = "DELETE FROM tblassembleproduct WHERE productcode = '".$arrproductcode[$vz]."' ";
				mysql_query($sql,get_db_conn());

				if(strlen(str_replace("","",$row->assemble_pridx))>0) {
					$sql = "UPDATE tblproduct SET ";
					$sql.= "assembleproduct = REPLACE(assembleproduct,',".$arrproductcode[$vz]."','') ";
					$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
					$sql.= "AND assembleuse != 'Y' ";
					mysql_query($sql,get_db_conn());
				}
			}
			mysql_free_result($result);
		} else {
			if(strlen($arrassembleproduct[$vz])>0) {
				$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
				$sql.= "WHERE productcode IN ('".str_replace(",","','",$arrassembleproduct[$vz])."') ";
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
			$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$arrpridx[$vz]."',''), ";
			$sql.= "assemble_list=REPLACE(assemble_list,',".$arrpridx[$vz]."','') ";
			mysql_query($sql,get_db_conn());
		}
	}

	for($yy=0;$yy<count($arrvender);$yy++) {
		//�̴ϼ� �׸��ڵ忡 ��ϵ� ��ǰ ����
		setVenderThemeDelete($prcodelist, $arrvender[$yy]);

		//�̴ϼ� ��ǰ�� ������Ʈ (������ ��ǰ��)
		$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
		$sql.= "WHERE vender='".$arrvender[$yy]."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$prdt_allcnt=(int)$row->prdt_allcnt;
		$prdt_cnt=(int)$row->prdt_cnt;
		mysql_free_result($result);

		setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $arrvender[$yy]);

		//tblvendercodedesign => �ش� ��з� ��ǰ Ȯ�� �� ������ ��з� ȭ�� ����
		$tmpcodeA=array();
		$arrprcode=explode("|",$allprcode);
		for($j=0;$j<count($arrprcode);$j++) {
			$tmpcodeA[substr($arrprcode[$j],0,3)]=true;
		}

		if(count($tmpcodeA)>0) {
			$sql = "SELECT SUBSTRING(productcode,1,3) as codeA FROM tblproduct ";
			$sql.= "WHERE ( ";
			$arr_codeA=$tmpcodeA;
			$i=0;
			while(list($key,$val)=each($arr_codeA)) {
				if(strlen($key)==3) {
					if($i>0) $sql.= "OR ";
					$sql.= "productcode LIKE '".$key."%' ";
					$i++;
				}
			}
			$sql.= ") ";
			$sql.= "AND vender='".$arrvender[$yy]."' ";
			$sql.= "GROUP BY codeA ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				unset($tmpcodeA[$row->codeA]);
			}
			mysql_free_result($result);

			if(count($tmpcodeA)>0) {
				$str_codeA="";
				while(list($key,$val)=each($tmpcodeA)) {
					$str_codeA.=$key.",";

					$imagename = $Dir.DataDir."shopimages/vender/".$arrvender[$yy]."_CODE10_".$key.".gif";
					@unlink($imagename);
				}
				$str_codeA=substr($str_codeA,0,-1);
				$str_codeA=ereg_replace(',','\',\'',$str_codeA);
				setVenderDesignDelete($str_codeA, $arrvender[$yy]);
			}
		}
	}

	$prcode = explode("|",$allprcode);
	$cnt = count($prcode);

	$log_content = "## ��ϻ�ǰ �̵�/����/�������� ".$cnt."���� ��ǰ���� ##";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	$wideimagedir = $Dir.DataDir."shopimages/wideimage/";
	for($i=0;$i<$cnt;$i++){
		$delshopimage=$Dir.DataDir."shopimages/product/".$prcode[$i]."*";
		$wideimage = $wideimagedir."*";

		proc_matchfiledel($wideimage);//���̵� �̹��� ����
		proc_matchfiledel($delshopimage);
		delProductMultiImg("prdelete","",$prcode[$i]);
	}

	$onload="<script>alert(\"".$cnt."���� ��ǰ�� ���������� �����Ǿ����ϴ�.\");</script>";
}

$sql = "SELECT vendercnt FROM tblshopcount ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$vendercnt=$row->vendercnt;
mysql_free_result($result);

if($vendercnt>0){
	$venderlist=array();
	$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$venderlist[$row->vender]=$row;
	}
	mysql_free_result($result);
}

$imagepath=$Dir.DataDir."shopimages/product/";
?>

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>//LH.add("parent_resizeIframe('ListFrame')");</script>

<script language="JavaScript">
<?if($vendercnt>0){?>
function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}
<?}?>

function ProductMouseOver(Obj) {
	obj = event.srcElement;
	WinObj=document.getElementById(Obj);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.display = "";

	if(!WinObj.height)
		WinObj.height = WinObj.offsetTop;

	WinObjPY = WinObj.offsetParent.offsetHeight;
	WinObjST = WinObj.height-WinObj.offsetParent.scrollTop;
	WinObjSY = WinObjST+WinObj.offsetHeight;

	if(WinObjPY < WinObjSY)
		WinObj.style.top = WinObj.offsetParent.scrollTop-WinObj.offsetHeight+WinObjPY;
	else if(WinObjST < 0)
		WinObj.style.top = WinObj.offsetParent.scrollTop;
	else
		WinObj.style.top = WinObj.height;
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	WinObj = document.getElementById(Obj);
	WinObj.style.display = "none";
	clearTimeout(obj._tid);
}

function GoPage(block,gotopage,sort) {
	document.form1.mode.value = "";
	document.form1.sort.value = sort;
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
}

function GoSort(sort) {
	document.form1.mode.value = "";
	document.form1.sort.value = sort;
	document.form1.block.value = "";
	document.form1.gotopage.value = "";
	document.form1.submit();
}

function CheckAll(cnt){
	checkvalue=document.form1.allcheck.checked;
	for(i=1;i<=cnt;i++){
		document.form1.cproductcode[i].checked=checkvalue;
		checkActive(document.form1.cproductcode[i],document.form1.cproductcode[i].value);
	}
}

function CopyCodeSelect() {
	window.open("product_copycodeselect.php","","height=300,width=420,scrollbars=no,resizable=no");
}

function Copy(gbn) {
	var gbn_name = "����";
	if (gbn=="move") gbn_name = "�̵�";
	if (document.form1.copycode.value.length==0) {
		alert(gbn_name+"�� ī�װ��� �����ϼ���.");
		CopyCodeSelect();
		return;
	}
	if (document.form1.copycode.value==document.form1.oldcode.value) {
		alert(gbn_name+"�� ī�װ��� ����ī�װ��� �����ϴ�.");
		CopyCodeSelect();
		return;
	}
	if (confirm("���õ� ī�װ��� "+gbn_name+"�Ͻðڽ��ϱ�?")) {
		var checkvalue=false;
		for(i=1;i<document.form1.cproductcode.length;i++){
			if(document.form1.cproductcode[i].checked==true){
				checkvalue=true;
				document.form1.cproductcodes.value+=document.form1.cproductcode[i].value+"|";
			}
		}

		if(checkvalue!=true){
			alert(gbn_name+"�� ��ǰ�� �����ϼ���");
			return;
		}
		document.form1.mode.value=gbn;
		document.form1.block.value="";
		document.form1.gotopage.value="";
		document.form1.submit();
	}
}

function Delete() {
	if (confirm("������ ��ǰ ������ ������ �Ұ����մϴ�.\n\n���õ� ��ǰ�� �����Ͻðڽ��ϱ�?")) {
		var checkvalue=false;
		for(i=1;i<document.form1.cproductcode.length;i++){
			if(document.form1.cproductcode[i].checked==true){
				checkvalue=true;
				document.form1.cproductcodes.value+=document.form1.cproductcode[i].value+"|";
			}
		}
		if(checkvalue!=true){
			alert('������ ��ǰ�� ���õ��� �ʾҽ��ϴ�.');
			return;
		}
		document.form1.mode.value="delete";
		document.form1.block.value="";
		document.form1.gotopage.value="";
		document.form1.submit();
	}
}

function checkActive(checkObj,checkId)
{
	if(document.getElementById("pidx_"+checkId))
	{
		if(checkObj.checked)
			document.getElementById("pidx_"+checkId).style.backgroundColor = "#EFEFEF";
		else
			document.getElementById("pidx_"+checkId).style.backgroundColor = "#FFFFFF";
	}
}

function ProductInfo(prcode) {
	code=prcode.substring(0,12);
	popup="YES";
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		document.form_reg.action="product_register.add.php";
		document.form_reg.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}

function DivDefaultReset()
{
	if(!self.id)
	{
		self.id = self.name;
		parent.document.getElementById(self.id).style.height = parent.document.getElementById(self.id).height;
	}
}
DivDefaultReset();
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff">
<tr>
	<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_mainlist_text.gif" border="0"></td>
</tr>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=cproductcodes>
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<input type=hidden name=searchtype value="<?=$searchtype?>">
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sort value="<?=$sort?>">
<tr>
	<td width="100%" height="100%" valign="top" style="BORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="100%" style="padding-top:2pt; padding-bottom:2pt;" height="30"><B><span class="font_orange">* ���Ĺ�� :</span></B> <A HREF="javascript:GoSort('date');">������</a> | <A HREF="javascript:GoSort('productname');">��ǰ���</a> | <A HREF="javascript:GoSort('price');">���ݼ�</a></td>
	</tr>
	<tr>
		<td width="100%" valign="top">
		<DIV style="width:100%;height:100%;overflow:hidden;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="100%">
			<TABLE border="0" cellSpacing="0" cellPadding="0" width="100%">
<?
			$colspan=7;
			if($vendercnt>0) $colspan++;
?>
			<col width=45></col>
			<?if($vendercnt>0){?>
			<col width=70></col>
			<?}?>
			<col width=50></col>
			<col width=></col>
			<col width=70></col>
			<col width=45></col>
			<col width=45></col>
			<col width=45></col>
			<TR>
				<TD colspan="<?=$colspan?>" background="images/table_top_line.gif"></TD>
			</TR>
			<TR align="center">
				<TD class="table_cell">����</TD>
				<?if($vendercnt>0){?>
				<TD class="table_cell1">������ü</TD>
				<?}?>
				<TD class="table_cell1" colspan="2">��ǰ��/�����ڵ�/Ư�̻���</TD>
				<TD class="table_cell1">�ǸŰ���</TD>
				<TD class="table_cell1">����</TD>
				<TD class="table_cell1">����</TD>
				<TD class="table_cell1">����</TD>
			</TR>
			<input type=hidden name=cproductcode>
<?
			if (($searchtype=="0" && strlen($code)==12) || ($searchtype=="1" && strlen($keyword)>2)) {
				$page_numberic_type = 1;
				if ($searchtype=="0" && strlen($code)==12) {
					$qry = "AND productcode LIKE '".$code."%' ";
				} else {
					$qry = "AND productname LIKE '%".$keyword."%' ";
				}
				$sql0 = "SELECT COUNT(*) as t_count FROM tblproduct WHERE 1=1 ";
				$sql0.= $qry;
				$result = mysql_query($sql0,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$t_count = $row->t_count;
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT option_price, productcode,productname,production,sellprice,consumerprice, ";
				$sql.= "buyprice,quantity,reserve,reservetype,addcode,display,vender,tinyimage,selfcode,assembleuse ";
				$sql.= "FROM tblproduct WHERE 1=1 ";
				$sql.= $qry." ";
				if ($sort=="price")				$sql.= "ORDER BY sellprice ";
				else if ($sort=="productname")	$sql.= "ORDER BY productname ";
				else							$sql.= "ORDER BY date DESC ";
				$sql.= " LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					echo "<tr>\n";
					echo "	<TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</tr>\n";
					echo "<tr align=\"center\" id=\"pidx_".$row->productcode."\">\n";
					echo "	<TD class=\"td_con2\"><input type=checkbox name=cproductcode value=\"".$row->productcode."\" onclick=\"checkActive(this,'".$row->productcode."')\"></td>\n";
					if($vendercnt>0) {
						echo "	<TD class=\"td_con1\"><B>".(strlen($venderlist[$row->vender]->vender)>0?"<a href=\"javascript:viewVenderInfo(".$row->vender.")\">".$venderlist[$row->vender]->id."</a>":"-")."</B></td>\n";
					}
					echo "<TD class=\"td_con1\">";
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
						echo "<img src='".$imagepath.$row->tinyimage."' height=40 width=40 border=1 onMouseOver=\"ProductMouseOver('primage".$cnt."')\" onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
					} else {
						echo "<img src=images/space01.gif onMouseOver=\"ProductMouseOver('primage".$cnt."')\" onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
					}
					echo "<div id=\"primage".$cnt."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
					echo "		<tr bgcolor=\"#FFFFFF\">\n";
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$row->tinyimage."\" border=\"0\"></td>\n";
					} else {
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$Dir."images/product_noimg.gif\" border=\"0\"></td>\n";
					}
					echo "		</tr>\n";
					echo "		</table>\n";
					echo "		</div>\n";
					echo "	</td>\n";
					echo "	<TD class=\"td_con1\" align=\"left\" style=\"word-break:break-all;\"><img src=\"images/producttype".($row->assembleuse=="Y"?"y":"n").".gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\">".$row->productname.($row->selfcode?"-".$row->selfcode:"").($row->addcode?"-".$row->addcode:"")."&nbsp;</td>\n";
					echo "	<TD align=right class=\"td_con1\"><img src=\"images/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\"><span class=\"font_orange\">".number_format($row->sellprice)."</span><br><img src=\"images/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".($row->reservetype!="Y"?number_format($row->reserve):$row->reserve."%")."</TD>\n";
					echo "	<TD class=\"td_con1\">";
					if (strlen($row->quantity)==0) echo "������";
					else if ($row->quantity<=0) echo "<span class=\"font_orange\"><b>ǰ��</b></span>";
					else echo $row->quantity;
					echo "	</TD>\n";
					echo "	<TD class=\"td_con1\">".($row->display=="Y"?"<font color=\"#0000FF\">�Ǹ���</font>":"<font color=\"#FF4C00\">������</font>")."</td>";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ProductInfo('".$row->productcode."');\"><img src=\"images/icon_newwin1.gif\" border=\"0\"></a></td>\n";
					echo "</tr>\n";
					$cnt++;
				}
				mysql_free_result($result);
				if ($cnt==0) {
					$page_numberic_type="";
					echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">�˻��� ��ǰ�� �������� �ʽ��ϴ�.</td></tr>";
				}
			} else {
				$page_numberic_type="";
				echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">��ǰī�װ��� �����ϰų� �˻��� �ϼ���.</td></tr>";
			}
?>
			<TR>
				<TD height="1" colspan="<?=$colspan?>" background="images/table_top_line.gif"></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		<tr>
			<td width="100%" background="images/blueline_bg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
<?
			echo "<tr>\n";
			echo "	<td class=\"font_blue\" style=\"padding-bottom:2px;\"><input type=checkbox id=\"idx_allcheck\" name=allcheck value=\"".$cnt."\" style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none\" onclick=\"CheckAll('".$cnt."')\"><label style=\"cursor:hand; TEXT-DECORATION: none\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_allcheck><span style=\"font-size:8pt;\">��ü��ǰ ����</span></label></td>";
			echo "</tr>\n";
			if($page_numberic_type) {


				$total_block = intval($pagecount / $setup[page_num]);

				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}

				$total_block = $total_block - 1;

				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href=\"javascript:GoPage(0,1,'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$prev_page_exists = true;
					}

					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href=\"javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

						$a_prev_page = $a_first_block.$a_prev_page;
					}

					// �Ϲ� �������� ������ ǥ�úκ�-����

					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
							if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href=\"javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					} else {
						if (($pagecount % $setup[page_num]) == 0) {
							$lastpage = $setup[page_num];
						} else {
							$lastpage = $pagecount % $setup[page_num];
						}

						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href=\"javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).",'".$sort."');\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					}		// ������ �������� ǥ�úκ�-��


					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);

						$a_last_block .= "&nbsp;&nbsp;<a href=\"javascript:GoPage(".$last_block.",".$last_gotopage.",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

						$next_page_exists = true;
					}

					// ���� 10�� ó���κ�...

					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= "&nbsp;&nbsp;<a href=\"javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>[1]</B>";
				}
				echo "<tr>\n";
				echo "	<td height=\"30\" align=center>\n";
				echo "	".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page."\n";
				echo "	</td>\n";
				echo "</tr>\n";
			}
?>
			</table>
			</td>
		</tr>
<?
		if ($t_count>0) {
?>
		<input type=hidden name=copycode value="<?=$copycode?>">
		<input type=hidden name=oldcode value="<?=$code?>">
		<tr>
			<td width="100%" bgcolor="#0099CC" style="padding-top:3pt; padding-bottom:3pt;">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" class="font_white1">
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="110"></col>
				<col width=""></col>
				<col width="95"></col>
				<tr>
					<td class="font_white1">&nbsp;&nbsp;�̵�/������ ī�װ� : </td>
					<td><input type=text name=copycode_name size=43 style="width:100%;" onfocus="this.blur();alert('[ī�װ� ����] ��ư�� �̿��ϼż� �̵�/�����ų ��ġ�� ī�װ��� �����Ͻñ� �ٶ��ϴ�.');" value="<?=htmlspecialchars(stripslashes($copycode_name))?>" class="input" style="width:100%;"></td>
					<td align=center><a href="javascript:CopyCodeSelect();"><img src="images/btn_cateselect.gif" border="0"></a></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width="100%" class="font_white1">&nbsp;<input type=checkbox id="idx_newtime" name=newtime value="Y" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_newtime>�̵�/����� ��ǰ�� ��ϳ�¥�� ����ð����� �缳���մϴ�.</label></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="100%" align=center style="padding-top:6pt; padding-bottom:6pt;"><span style="font-size:8pt; letter-spacing:-0.5pt;" class="font_orange">��ǰ �̵�/����� <b>������ �Ǵ� ������ ī�װ������� ����</b>�˴ϴ�.</span><br>
		<a href="javascript:Copy('copy');"><img src="images/btn_copy.gif" width="136" height="38" border="0" vspace="3"></a>&nbsp;
		<a href="javascript:Copy('move');"><img src="images/btn_trans.gif" width="136" height="38" border="0" vspace="3"></a>&nbsp;
		<a href="javascript:Delete();"><img src="images/btn_del4.gif" width="136" height="38" border="0" vspace="3"></a></td>
	</tr>
	</table>
	</td>
</tr>

<?
		}
?>
</form>
<form name=form_reg action="product_register.php" method=post>
<input type=hidden name=code>
<input type=hidden name=prcode>
<input type=hidden name=popup>
</form>
<?if($vendercnt>0){?>
<form name=vForm action="vender_infopop.php" method=post>
<input type=hidden name=vender>
</form>
<?}?>
</table>
<?=$onload?>
<script type="text/javascript">
<!--
	parent.autoResize('ListFrame');
//-->
</script>
</body>
</html>
