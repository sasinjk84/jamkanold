<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/member_func.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$imagepath=$Dir.DataDir."shopimages/multi/";

$productcode=$_REQUEST["productcode"];
$qftype=$_GET["qftype"];
$bttype=$_GET["bttype"];
$opts=$_GET["opts"];
$option1=$_GET["option1"];
$option2=$_GET["option2"];
$mode=$_GET["mode"];
$code=$_GET["code"];
$ordertype=$_GET["ordertype"];	//�ٷα��� ���� (�ٷα��Ž� => ordernow)
$quantity=(int)$_REQUEST["quantity"];	//���ż���
if($quantity==0) $quantity=1;

$bookingStartDate=$_REQUEST["bookingStartDate"];
$bookingEndDate=$_REQUEST["bookingEndDate"];

// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
$basket = basketTable($ordertype);

$errmsg="";
if(strlen($productcode)==18) {
	$codeA=substr($productcode,0,3);
	$codeB=substr($productcode,3,3);
	$codeC=substr($productcode,6,3);
	$codeD=substr($productcode,9,3);

	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_cdata=$row;
		if($row->group_code=="NO") {	//���� �з�
			$errmsg="�ǸŰ� ����� ��ǰ�Դϴ�.";
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//ȸ���� ���ٰ���
			$errmsg="ȸ������ ��ǰ�Դϴ�.\\n\\n�α��� �� �̿��Ͻñ� �ٶ��ϴ�.";
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//�׷�ȸ���� ����
			$errmsg="�ش� �з��� ���� ������ �����ϴ�.";
		} else {
			if (empty($option1))  $option1=0;
			if (empty($option2))  $option2=0;
			if (empty($opts))  $opts="0";

			//Wishlist ���
			if($mode=="wishlist") {
				if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
					$errmsg="�α����� �ϼž� �� ���񽺸� �̿��Ͻ� �� �ֽ��ϴ�.";
				} else {
					$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check FROM tblproduct ";
					$sql.= "WHERE productcode='".$productcode."' ";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						if($row->display!="Y") {
							$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
						}
						if($row->group_check!="N") {
							if(strlen($_ShopInfo->getMemid())>0) {
								$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
								$sqlgc.= "WHERE productcode='".$productcode."' ";
								$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
								$resultgc=mysql_query($sqlgc,get_db_conn());
								if($rowgc=@mysql_fetch_object($resultgc)) {
									if($rowgc->groupcheck_count<1) {
										$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
									}
									@mysql_free_result($resultgc);
								} else {
									$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
								}
							} else {
								$errmsg="�ش� ��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
							}
						}
						if(strlen($errmsg)==0) {
							if(strlen(dickerview($row->etctype,0,1))>0) {
								$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʽ��ϴ�.\\n";
							}
						}
						if(empty($option1) && strlen($row->option1)>0)  $option1=1;
						if(empty($option2) && strlen($row->option2)>0)  $option2=1;
					} else {
						$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.\\n";
					}
					mysql_free_result($result);

					if(!$errmsg)
					{
						$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
						$result2=mysql_query($sql,get_db_conn());
						$row2=mysql_fetch_object($result2);
						$totcnt=$row2->totcnt;
						mysql_free_result($result2);
						$maxcnt=100;
						if($totcnt>=$maxcnt) {
							$sql = "SELECT b.productcode ";
							$sql.= "FROM tblwishlist a, tblproduct b ";
							$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
							$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
							$sql.= "AND b.display='Y' ";
							$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
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
								$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
								$sql.= "AND productcode NOT IN (".$wishprcode.") ";
								mysql_query($sql,get_db_conn());
							}
						}
						if($totcnt<$maxcnt) {
							$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
							$sql.= "AND productcode='".$productcode."' AND opt1_idx='".$option1."' ";
							$sql.= "AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
							$result2=mysql_query($sql,get_db_conn());
							$row2=mysql_fetch_object($result2);
							$cnt=$row2->cnt;
							mysql_free_result($result2);
							if($cnt<=0) {
								$sql = "INSERT tblwishlist SET ";
								$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
								$sql.= "productcode	= '".$productcode."', ";
								$sql.= "opt1_idx	= '".$option1."', ";
								$sql.= "opt2_idx	= '".$option2."', ";
								$sql.= "optidxs		= '".$opts."', ";
								$sql.= "date		= '".date("YmdHis")."' ";
								mysql_query($sql,get_db_conn());
							} else {
								$sql = "UPDATE tblwishlist SET date='".date("YmdHis")."' ";
								$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
								$sql.= "AND productcode='".$productcode."' ";
								$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
								mysql_query($sql,get_db_conn());
							}

							echo "<script type=\"text/javascript\">
							if(confirm('WishList�� �ش� ��ǰ�� ����Ͽ����ϴ�.\\n\\n  WishList �������� �̵� �ϰڽ��ϱ�?')) { location.href='".$Dir.FrontDir."wishlist.php'; }
							".(strlen($bttype)>0?"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Wishlist','noselectmenu'); } }":"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Wishlist','selectmenu'); } }")."
							</script>"; exit;
						} else {
							echo "<script type=\"text/javascript\">
							if(confirm('1. WishList���� ".$maxcnt."�� ������ ����� �����մϴ�.\\n2. �� ��ǰ�� ��� ���ؼ��� ���� WishList ��ǰ�� ���� �� ����� �� �ֽ��ϴ�.\\n\\n                    WishList �������� �̵� �ϰڽ��ϱ�?'))
								location.href='".$Dir.FrontDir."wishlist.php';
							</script>"; exit;
						}
					}
				}
			}
			else if($mode=="basket_insert") {//��ٱ��� ���
				//��ٱ��� ����Ű Ȯ��
				if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
					$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
				}

				//��ٱ��ϴ��, �ٷα���
				if(strlen($quantity)>0 && strlen($productcode)==18) {//��ٱ��� ���
					$rowcnt=$quantity;

					$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check FROM tblproduct ";
					$sql.= "WHERE productcode='".$productcode."' ";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						if($row->display!="Y") {
							$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
						}
						if($row->group_check!="N") {
							if(strlen($_ShopInfo->getMemid())>0) {
								$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
								$sqlgc.= "WHERE productcode='".$productcode."' ";
								$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
								$resultgc=mysql_query($sqlgc,get_db_conn());
								if($rowgc=@mysql_fetch_object($resultgc)) {
									if($rowgc->groupcheck_count<1) {
										$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
									}
									@mysql_free_result($resultgc);
								} else {
									$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
								}
							} else {
								$errmsg="�ش� ��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
							}
						}
						if(strlen($errmsg)==0) {
							$miniq=1;
							$maxq="?";
							if(strlen($row->etctype)>0) {
								$etctemp = explode("",$row->etctype);
								for($i=0;$i<count($etctemp);$i++) {
									if(substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);
									if(substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
								}
							}

							if(strlen(dickerview($row->etctype,0,1))>0) {
								$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
							}
						}
						if(strlen($errmsg)==0) {
							if ($miniq!=1 && $miniq>1 && $rowcnt<$miniq)
								$errmsg="�ش� ��ǰ�� �ּ� ".$miniq."�� �̻� �ֹ��ϼž� �մϴ�.\\n";
							if ($maxq!="?" && $maxq>0 && $rowcnt>$maxq)
								$errmsg.="�ش� ��ǰ�� �ִ� ".$maxq."�� ���Ϸ� �ֹ��ϼž� �մϴ�.\\n";

							if(empty($option1) && strlen($row->option1)>0)  $option1=1;
							if(empty($option2) && strlen($row->option2)>0)  $option2=1;
							if(strlen($row->quantity)>0) {
								if ($rowcnt>$row->quantity) {
									if ($row->quantity>0)
										$errmsg.="�ش� ��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
									else
										$errmsg.= "�ش� ��ǰ�� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
								}
							}
							if(strlen($row->option_quantity)>0) {
								$optioncnt = explode(",",substr($row->option_quantity,1));
								if($option2==0) $tmoption2=1;
								else $tmoption2=$option2;
								$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
								if($optionvalue<=0 && $optionvalue!="")
									$errmsg.="�ش� ��ǰ�� ���õ� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
								else if($optionvalue<$quantity && $optionvalue!="")
									$errmsg.="�ش� ��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
							}
						}
					} else {
						$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.\\n";
					}
					mysql_free_result($result);
				} else {
					$errmsg = "���ż����� �߸��Ǿ����ϴ�.";
				}

				if(!$errmsg)
				{
					$sql = "SELECT * FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' ";
					$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
					$result = mysql_query($sql,get_db_conn());
					$row=mysql_fetch_object($result);
					mysql_free_result($result);
					if ($row) {
						echo "<script type=\"text/javascript\">
							if(confirm('�̹� ��ٱ��Ͽ� ��ǰ�� ����ֽ��ϴ�. ������ ��ٱ��� ���ż� ������ �ּ���.\\n\\n                    ��ٱ��� �������� �̵� �ϰڽ��ϱ�?'))
								location.href='".$Dir.FrontDir."basket.php';
						</script>"; exit;
					} else {
						if (strlen($productcode)==18) {

							// �뿩 �Ⱓ ���� üũ
							$pridx = productcodeToPridx($productcode);
							if( rentOrderChecker ( $pridx, $bookingStartDate, $bookingEndDate, $rentOptionList ) != "pass" ) {
								echo "<script>alert('�뿩 ������ �������� Ȯ�� �Ͻñ� �ٶ��ϴ�.\\n������Ȳ�� Ȯ���ϼ���!'); bookingSchedulePop(".$pridx.");</script>";
								exit;
							}

							$vdate = date("YmdHis");
							$sql = "SELECT COUNT(*) as cnt FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
							$result = mysql_query($sql,get_db_conn());
							$row = mysql_fetch_object($result);
							mysql_free_result($result);
							if($row->cnt>=200) {
								echo "<script type=\"text/javascript\">
									if(confirm('1. ��ٱ��Ͽ��� �� 200�� ������ ���� �� �ֽ��ϴ�.\\n2. �� ��ǰ�� ��� ���ؼ��� ���� ��ٱ��� ��ǰ�� ���� �� ���� �� �ֽ��ϴ�.\\n\\n                    ��ٱ��� �������� �̵� �ϰڽ��ϱ�?'))
										location.href='".$Dir.FrontDir."basket.php';
									</script>"; exit;
							} else {

								if($ordertype=="ordernow") {	//�ٷα���
									//��ٱ��� ����
									$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
									mysql_query($sql,get_db_conn());
								}

								$sql = "INSERT ".$basket." SET ";
								$sql.= "tempkey		= '".$_ShopInfo->getTempkey()."', ";
								$sql.= "productcode	= '".$productcode."', ";
								$sql.= "opt1_idx	= '".$option1."', ";
								$sql.= "opt2_idx	= '".$option2."', ";
								$sql.= "optidxs		= '".$opts."', ";
								$sql.= "quantity	= '".$quantity."', ";
								$sql.= "date		= '".$vdate."', ";
								$sql.= "ordertype		= '".$ordertype."' ";
								mysql_query($sql,get_db_conn());


								// �뿩 ��ٱ��� ��ǰ �뿩�Ⱓ ����
								if( strlen($bookingStartDate) == 8 AND strlen($bookingEndDate) == 8 AND strlen($rentOptionList) > 0 ) {
									rentBasketSave ( $_ShopInfo->getTempkey(), $basket, $productcode, $bookingStartDate, $bookingEndDate, $rentOptionList );
								}

								if($ordertype=="ordernow") {	//�ٷα���
									echo "<script type=\"text/javascript\">location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode( $Dir.FrontDir."order.php?ordertype=ordernow" )."';</script>";
									exit;
								} else {
									echo "
									<script type=\"text/javascript\">
										if(confirm('��ٱ��Ͽ� �ش� ��ǰ�� ����Ͽ����ϴ�.\\n\\n��ٱ��� �������� �̵� �ϰڽ��ϱ�?')) { location.href='".$Dir.FrontDir."basket.php'; }
										".(strlen($bttype)>0?"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Basket','noselectmenu'); } }":"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu');  setFollowFunc('Basket','selectmenu'); } }")."
									</script>"; exit;
								}
							}
						}
					}
				}
			}
			else
			{
				$sql = productQuery ();
				$sql.= "WHERE a.productcode='".$productcode."' AND a.display='Y' ";
				$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$_pdata=$row;
				} else {
					$errmsg="�ش� ��ǰ ������ �������� �ʽ��ϴ�.";
				}
				mysql_free_result($result);
			}
		}
	} else {
		$errmsg="�ش� �з��� �������� �ʽ��ϴ�.";
	}
} else {
	$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.";
}

if(strlen($errmsg)>0) {
	echo "<script type=\"text/javascript\">alert('".$errmsg."'); $('create_openwin').setStyle('display','none');</script>"; exit;
}

if($_pdata->assembleuse=="Y" && $qftype != 1) {
	echo "<script type=\"text/javascript\">if(confirm('�ش� ��ǰ�� ��ǰ������������ ������ǰ�� ���� �Ŀ��� ���Ű� �����մϴ�.\\n\\n                     ��ǰ���������� �̵� �ϰڽ��ϱ�?')) {location.href='".$Dir.FrontDir."productdetail.php?productcode=".$productcode."';} $('create_openwin').setStyle('display','none');</script>"; exit;
}

if((int)$_pdata->package_num>0 && $qftype != 1) {
	echo "<script type=\"text/javascript\">if(confirm('�ش� ��ǰ�� ��Ű�� ���� ��ǰ���ν� ��ǰ������������ ��Ű�� ������ Ȯ�� �� �ּ���.\\n\\n                              ��ǰ���������� �̵� �ϰڽ��ϱ�?')) {location.href='".$Dir.FrontDir."productdetail.php?productcode=".$productcode."';} $('create_openwin').setStyle('display','none');</script>"; exit;
}

$ref=$_REQUEST["ref"];
if (strlen($ref)==0) {
	$ref=strtolower(ereg_replace("http://","",getenv("HTTP_REFERER")));
	if(strpos($ref,"/") !== false) $ref=substr($ref,0,strpos($ref,"/"));
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
	if(strlen($bttype)>0) {
		$viewproduct=$_COOKIE["ViewProduct"];
		if(strrpos(" ".$viewproduct,",".$productcode.",")!==false) {
			if(strlen($viewproduct)==0) {
				$viewproduct=",".$productcode.",";
			} else {
				$viewproduct=",".$productcode.$viewproduct;
			}
			$viewproduct=substr($viewproduct,0,172);
			setcookie("ViewProduct",$viewproduct,0,"/");
		}
	} else {
		$viewproduct=$_COOKIE["ViewProduct"];
		if(strrpos(" ".$viewproduct,",".$productcode.",")!==false) {
			if(strlen($viewproduct)==0) {
				$viewproduct=",".$productcode.",";
			} else {
				$viewproduct=",".$productcode.$viewproduct;
			}
		} else {
			$viewproduct=str_replace(",".$productcode.",",",",$viewproduct);
			$viewproduct=",".$productcode.$viewproduct;
		}
		$viewproduct=substr($viewproduct,0,172);
		setcookie("ViewProduct",$viewproduct,0,"/");
	}
}

#####################��ǰ�� ȸ�������� ���� ����#######################################
// ���� ���� ���� ��ǰ ������
$wholeSaleIcon = ( $_pdata->isdiscountprice == 1 ) ? $wholeSaleIconSet:"";

$memberpriceValue = $_pdata->sellprice;
$strikeStart = $strikeEnd = $memberprice = '';
if($_pdata->discountprices>0){
	$memberprice = number_format($_pdata->sellprice - $_pdata->discountprices);
	$strikeStart = "<strike>";
	$strikeEnd = "</strike> �� ".$memberprice;
	$memberpriceValue = ($_pdata->sellprice - $_pdata->discountprices);
}
#####################��ǰ�� ȸ�������� ���� �� #######################################

//��ǰ ������ ��������
if(strlen($_data->exposed_list)==0) {
	$_data->exposed_list=",0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,";
}
$arexcel = explode(",",substr($_data->exposed_list,1,-1));
$prcnt = count($arexcel);
$arproduct=array(&$prproduction,&$prmadein,&$prconsumerprice,&$prsellprice,&$prreserve,&$praddcode,&$prquantity,&$proption,&$prproductname,&$prdollarprice,&$rentaloption,&$rental,&$rentalView,&$rentalCal,&$rentalSeason1,&$rentalSeason2,&$rentalSeason3,&$priceCalc);

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
	}
}

//������ü ���� ����
if($_pdata->vender>0) {
	$sql = "SELECT a.vender, a.id, a.brand_name, a.deli_info, b.prdt_cnt ";
	$sql.= "FROM tblvenderstore a, tblvenderstorecount b ";
	$sql.= "WHERE a.vender='".$_pdata->vender."' AND a.vender=b.vender ";
	$result=mysql_query($sql,get_db_conn());
	if(!$_vdata=mysql_fetch_object($result)) {
		$_pdata->vender=0;
	}
	mysql_free_result($result);
}
?>
<?

// �뿩 ��ǰ ����
	$prproductname="";

	if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0) {
		$prsellprice=$dicker;
		$prdollarprice="";
		$priceindex=0;
	} else if(strlen($optcode)==0 && strlen($_pdata->option_price)>0) {
		$option_price = $_pdata->option_price;
		$pricetok=explode(",",$option_price);
		$priceindex = count($pricetok);
		for($tmp=0;$tmp<=$priceindex;$tmp++) {
			$pricetok[$tmp]=number_format($pricetok[$tmp]);
		}
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\" />".( _array($rentLocalInfo) ? "�뿩��" : "�ǸŰ���" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>". $strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=\"hidden\" name=\"price\" value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
	} else if(strlen($optcode)>0) {
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\" />".( _array($rentLocalInfo) ? "�뿩��" : "�ǸŰ���" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=\"hidden\" name=\"price\" value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
	} else if(strlen($_pdata->option_price)==0) {
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\" />".( _array($rentLocalInfo) ? "�뿩��" : "�ǸŰ���" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\" /><b>".$strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=\"hidden\" name=\"price\" value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
		$priceindex=0;
	}

	if($qftype == 1)
		$prquantity ="<input type=\"hidden\" name=\"quantity\" value=\"1\" />\n";
	else
	{
		$prquantity.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">���ż���</td>\n";
		$prquantity.="<td></td>";
		$prquantity.="<td align=\"left\">\n";
		$prquantity.="<table cellpadding=\"1\" cellspacing=\"0\" width=\"60\">\n";
		$prquantity.="<tr>\n";
		$prquantity.="	<td width=\"33\"><input type=text name=\"quantity\" value=\"".($miniq>1?$miniq:"1")."\" size=\"4\" style=\"font-size:11px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;\" onkeyup=\"strnumkeyup(this)\"></td>\n";
		$prquantity.="	<td width=\"33\">\n";
		$prquantity.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$prquantity.="	<tr>\n";
		$prquantity.="		<td width=\"5\" height=\"7\"><a href=\"javascript:quickfun_change_quantity('up')\"><img src=\"".$Dir."images/common/btn_plus.gif\" border=\"0\"></a></td>\n";
		$prquantity.="	</tr>\n";
		$prquantity.="	<tr>\n";
		$prquantity.="		<td width=\"5\" height=\"7\"><a href=\"javascript:quickfun_change_quantity('dn')\"><img src=\"".$Dir."images/common/btn_minus.gif\" border=\"0\"></a></td>\n";
		$prquantity.="	</tr>\n";
		$prquantity.="	</table>\n";
		$prquantity.="	</td>\n";
		$prquantity.="	<td width=\"33\">EA</td>\n";
		$prquantity.="</tr>\n";
		$prquantity.="</table>\n";
		$prquantity.="</td>\n";
	}



// �뿩
if($qftype != 1){
	$rentDetail = rentProduct::read($_pdata->pridx);
	if(!$rentDetail) break;
	
	$rental.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">�뿩�Ⱓ</td>\n";
	$rental.="<td></td>";
	$rental.="<td align=\"left\">\n";
	$rental.="<table cellpadding=\"1\" cellspacing=\"0\" width=\"60\">\n";
	$rental.="<tr>\n";
	$rental.="	<td width=\"33\">\n";
	$rental.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$rental.="	<tr>\n";
	$rental.="		<td width=\"5\" height=\"7\">
						<span id=\"qp_bookingStartDateCal\" style=\"position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;\"></span>
						<input type=\"text\" name=\"p_bookingStartDate\" id=\"qp_bookingStartDate\" value=\"" . date ("Ymd") . "\" style=\"width:60px;\" onclick=\"qp_bookingStartDateCal.style.display=(qp_bookingStartDateCal.style.display=='none' ? 'block' : 'none' );\" readonly>
					</td>
					<td>
						-
					</td>
					<td>
						<span id=\"qp_bookingEndDateCal\" style=\"position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;\"></span>
						<input type=\"text\" name=\"p_bookingEndDate\" id=\"qp_bookingEndDate\" value=\"" . date ("Ymd") . "\" style=\"width:60px;\" onclick=\"qp_bookingEndDateCal.style.display=(qp_bookingEndDateCal.style.display=='none' ? 'block' : 'none' );\" readonly>
					</td>\n";
	$rental.="	</tr>\n";
	$rental.="	</table>\n";
	$rental.="	</td>\n";
	$rental.="</tr>\n";
	$rental.="</table>\n";
	$rental.="</td>\n";
	$rental .= "
				<script>
					show_cal('" . date ("Ymd") . "','qp_bookingStartDateCal','qp_bookingStartDate','todayNext');
					show_cal('" . date ("Ymd") . "','qp_bookingEndDateCal','qp_bookingEndDate','todayNext');
				</script>
				";
	$rental .= "<input type='hidden' value='rental' name='rentStatus'>";



	$rentalView="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">�����Ȳ</td>\n";
	$rentalView.="<td></td>";
	$rentalView.="<td align=\"left\">\n";
	$rentalView.="<input type='button' onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" value=\"��Ȳ����\">";
	$rentalView.="</td>\n";



	$rentalCal="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">��������޷�</td>\n";
	$rentalCal.="<td></td>";
	$rentalCal.="<td align=\"left\">\n";
	$rentalCal.="		<input type='button' onclick=\"bookingPriceCalendalPop();\" value=\"�޷º���\">";
	$rentalCal.="</td>\n";


	$priceCalc="<td align=\"right\" style=\"word-break:break-all;\"><!-- <IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">���̸����� --></td>\n";
	$priceCalc.="<td></td>";
	$priceCalc.="<td align=\"left\">\n";
	$priceCalc.="	<input type=\"hidden\" name=\"pridx\" value=\"".$_pdata->pridx."\">
					<!-- <input type=\"button\" value=\"����ϱ�\" onclick=\"priceCalc(this.form);\"> -->
					<div id=\"priceCalcPrint\" style=\"padding-left:40px;color:#ec2f36;\"></div>
	";
	$priceCalc.="</td>\n";




	// ���𰡰�
	/*
	$seasonPrice = rentProductSeasonPrice($_pdata->pridx);
	$rentalSeason1 = "";
	if ( $seasonPrice['busySeason'] > 0 ) {
		$rentalSeason1.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">������</td>\n";
		$rentalSeason1.="<td></td>";
		$rentalSeason1.="<td align=\"left\">\n";
		$rentalSeason1.="		".number_format($seasonPrice['busySeason'])." �߰�";
		$rentalSeason1.="</td>\n";
	}
	$rentalSeason2 = "";
	if ( $seasonPrice['semiBusySeason'] > 0 ) {
		$rentalSeason2.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">�ؼ�����</td>\n";
		$rentalSeason2.="<td></td>";
		$rentalSeason2.="<td align=\"left\">\n";
		$rentalSeason2.="		".number_format($seasonPrice['semiBusySeason'])." �߰�";
		$rentalSeason2.="</td>\n";
	}
	$rentalSeason3 = "";
	if ( $seasonPrice['holidaySeason'] > 0 ) {
		$rentalSeason3.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">�ָ����</td>\n";
		$rentalSeason3.="<td></td>";
		$rentalSeason3.="<td align=\"left\">\n";
		$rentalSeason3.="		".number_format($seasonPrice['holidaySeason'])." �߰�";
		$rentalSeason3.="</td>\n";
	}
	*/

	// ��Ż��ǰ �ɼ�

	$rentaloption = "";
	$rentaloption.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">�뿩�ɼ�</td>\n";
	$rentaloption.="<td></td>";
	$rentaloption.="<td align=\"left\">\n";
	$rentaloption .= "<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	
	foreach($rentDetail['options'] as $listROW) {
		$rentaloption .= "
			<tr align='center'>
				<td>" . $listROW['optionName'] . "
					<div style=\"position:absolute;\">
						<div id=\"rentOptionsPrices_" . $listROW['idx'] . "\" style=\"position:relative;display:none;\">
							<table width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"FFFFFF\">
								<tr>
									<td>�Ϲݰ�</td>
									<td>" . number_format($listROW['nomalPrice']) . "</td>
								</tr>";
		if($rentDetail['codeinfo']['useseason'] == '1'){ 
			$rentaloption .= "<tr>
									<td>������</td>
									<td> " . number_format($listROW['nomalPrice'] + $listROW['busySeason']) . "</td>
								</tr>
								<tr>
									<td>�ؼ�����</td>
									<td>" . number_format($listROW['nomalPrice'] + $listROW['semiBusySeason']) . "</td>
								</tr>
								<tr>
									<td>�ָ���</td>
									<td>" . number_format($listROW['nomalPrice'] + $listROW['holidaySeason']) . "</td>
								</tr>";
		}
		$rentaloption .= "		
							</table>
						</div>
					</div>
				</td>
				<td>" . rentProduct::_status($listROW['grade'])."</td>
				<td width='40'>
					<input type='text' value='0' style=\"width: 30px\" name='rentOptions' idxcode=\"" . $listROW['idx'] . "\" onFocus=\"rentOptionsPrices_" . $listROW['idx'] . ".style.display='inline';\" Onblur=\"rentOptionsPrices_" . $listROW['idx'] . ".style.display='none';\" onkeypress=\"priceCalc(this.form);\" onkeyup=\"priceCalc(this.form);\">��
				</td>
			</tr>";
	}
	$rentaloption .= "</table>\n";
	$rentaloption .= "<input type='hidden' value='' name='rentOptionList'>";
	$rentaloption .="</td>\n";
}else{
	if (strlen($_pdata->option1) > 0) {
		$temp = $_pdata->option1;
		$tok = explode(",", $temp);
		$count = count($tok);
		$proption1 .= "<tr height=\"22\">\n";
		$proption1 .= "	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"" . $Dir . "images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">$tok[0]</td>\n";
		$proption1 .= "	<td></td>\n";
		$proption1 .= "	<td align=\"left\">";
		if ($priceindex != 0) {
			$proption1 .= "<select name=\"option1\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
			$proption1 .= "onchange=\"quickfun_change_price(1,document.quickfun_form1.option1.selectedIndex-1,";
			if (strlen($_pdata->option2) > 0) $proption1 .= "document.quickfun_form1.option2.selectedIndex-1";
			else $proption1 .= "''";
			$proption1 .= ")\">\n";
		} else {
			$proption1 .= "<select name=\"option1\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
			$proption1 .= "onchange=\"quickfun_change_price(0,document.quickfun_form1.option1.selectedIndex-1,";
			if (strlen($_pdata->option2) > 0) $proption1 .= "document.quickfun_form1.option2.selectedIndex-1";
			else $proption1 .= "''";
			$proption1 .= ")\">\n";
		}

		$optioncnt = explode(",", substr($_pdata->option_quantity, 1));
		$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
		$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
		for ($i = 1; $i < $count; $i++) {
			if (strlen($tok[$i]) > 0) $proption1 .= "<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
			if (strlen($_pdata->option2) == 0 && $optioncnt[$i - 1] == "0") $proption1 .= " (ǰ��)";
		}
		$proption1 .= "</select>";
	} else {
		//$proption1.="<input type=hidden name=option1>";
	}

	$proption2="";
	if(strlen($_pdata->option2)>0) {
		$temp = $_pdata->option2;
		$tok = explode(",",$temp);
		$count2=count($tok);

		$proption2.="<tr height=\"22\">\n";
		$proption2.="	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">$tok[0]</td>\n";
		$proption2.="	<td></td>\n";
		$proption2.="	<td align=\"left\">";
		$proption2.="<select name=\"option2\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
		$proption2.="onchange=\"quickfun_change_price(0,";
		if(strlen($_pdata->option1)>0) $proption2.="document.quickfun_form1.option1.selectedIndex-1";
		else $proption2.="''";
		$proption2.=",document.quickfun_form1.option2.selectedIndex-1)\">\n";
		$proption2.="<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
		$proption2.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
		for($i=1;$i<$count2;$i++) if(strlen($tok[$i])>0) $proption2.="<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
		$proption2.="</select>";
		$proption2.="	</td>\n";
		$proption2.="</tr>\n";

	} else {
		if(strlen($_pdata->option1)>0) {
		$proption1.="	</td>\n";
		$proption1.="</tr>\n";
		}
	}

	if(strlen($optcode)>0) {
		$sql = "SELECT * FROM tblproductoption WHERE option_code='".$optcode."' ";
		$result = mysql_query($sql,get_db_conn());
		if($row = mysql_fetch_object($result)) {
			$optionadd = array (&$row->option_value01,&$row->option_value02,&$row->option_value03,&$row->option_value04,&$row->option_value05,&$row->option_value06,&$row->option_value07,&$row->option_value08,&$row->option_value09,&$row->option_value10);
			$opti=0;
			$option_choice = $row->option_choice;
			$exoption_choice = explode("",$option_choice);
			while(strlen($optionadd[$opti])>0) {
				$proption3.="[OPT]";
				$proption3.="<select name=\"mulopt\" size=1 style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" onchange=\"quickfun_chopprice('$opti')\"";
				$proption3.=">";
				$opval = str_replace('"','',explode("",$optionadd[$opti]));
				$proption3.="<option value=\"0,0\" style=\"color:#ffffff;\">--- ".$opval[0].($exoption_choice[$opti]==1?"(�ʼ�)":"(����)")." ---";
				$opcnt=count($opval);
				for($j=1;$j<$opcnt;$j++) {
					$exop = str_replace('"','',explode(",",$opval[$j]));
					$proption3.="<option value=\"".$opval[$j]."\" style=\"color:#ffffff;\">";
					if($exop[1]>0) $proption3.=$exop[0]."(+".$exop[1]."��)";
					else if($exop[1]==0) $proption3.=$exop[0];
					else $proption3.=$exop[0]."(".$exop[1]."��)";
				}
				$proption3.="</select><input type=hidden name=\"opttype\" value=\"0\"><input type=hidden name=\"optselect\" value=\"".$exoption_choice[$opti]."\">[OPTEND]";
				$opti++;
			}
			$proption3.="<input type=hidden name=\"mulopt\"><input type=hidden name=\"opttype\"><input type=hidden name=\"optselect\">";
		}
		mysql_free_result($result);
	}
}
?>
<form name="quickfun_form1" method="post" action="<?=$Dir.FrontDir?>basket.php">
	<input type="hidden" name="code" value="<?=$code?>">
	<input type="hidden" name="productcode" value="<?=$productcode?>">
	<input type="hidden" name="bttype" value="<?=$bttype?>">
	<input type="hidden" name="ordertype">
	<input type="hidden" name="opts">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td id="layerbox-top" style="cursor:move; float:left;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
								<col width="10">
									</col>
								
								<col width="">
									</col>
								
								<col width="10">
									</col>
								
								<tr>
									<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_left.gif) no-repeat 0 0;"></td>
									<td style="height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_center.gif)"><table border="0" cellpadding="0" cellspacing="0" width="100%">
											<col width="">
												</col>
											
											<col width="50">
												</col>
											
											<tr>
												<td style="padding:5,0,0,0; font-size:11px;color:#FEEACB;"><B>
													<? if($qftype == 1) echo "WishList ���"; else if($qftype == 2) echo "��ٱ��� ���"; else echo "�ٷα���";?>
													</B></td>
												<td align="right" style="padding-top:2;"><a style="cursor:hand" onclick="PrdtQuickCls.openwinClose()"><FONT style="font-size:11px;color:#FEEACB;">close</FONT> <img src="/<?=RootPath?>images/common/layeropen_btn_close.gif" border="0" align="absmiddle" /></a></td>
											</tr>
										</table></td>
									<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_right.gif) no-repeat 0 0;"></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<td id="layerbox-content"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
								<col width="10">
									</col>
								
								<col width="">
									</col>
								
								<col width="10">
									</col>
								
								<tr>
									<td style="width:10px;background: url(/<?=RootPath?>images/common/layeropenbg_middle_left.gif) repeat-y;"></td>
									<td style="background: url(/<?=RootPath?>images/common/layeropenbg_middle_center.gif);"><div style="margin: 15px 0 0 3px;"> 
											<!-- ���� ���� -->
											<table border="0" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td style="word-break:break-all;"><?if(strlen($_pdata->addcode)>0) echo "<font style=\"color:#FF7900;font-size:12px\"><B>[".$_pdata->addcode."]</B></font> ";
				echo "		<FONT style=\"color:#000000;font-size:12px\"><B>".viewproductname($_pdata->productname,$_pdata->etctype,$_pdata->selfcode)."</B></FONT>";?></td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#fafafa"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#efefef"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#fafafa"></td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
															<tr>
																<td valign="top"><!-- ��ǰ �󼼳��� ��� ���� -->
																	
																	<table border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tr>
																			<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
																					<col width="100">
																						</col>
																					
																					<col width="10">
																						</col>
																					
																					<col width="">
																						</col>
																					
																					<?
						for($i=0;$i<$prcnt;$i++) {
							if(substr($arexcel[$i],0,1)=="O") {	//����
								echo "<tr><td colspan=\"4\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>\n";
							} else if ($arexcel[$i]=="7") {	//�ɼ�
								if(strlen($proption1)>0 || strlen($proption2)>0 || strlen($proption3)>0) {

									if(strlen($proption1)>0) {
										$proption.=$proption1;
									}
									if(strlen($proption2)>0) {
										$proption.=$proption2;
									}
									if(strlen($proption3)>0) {
										$add_proption ="<tr height=\"22\">\n";
										$add_proption.="	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">��ǰ�ɼ�</td>\n";
										$add_proption.="	<td></td>\n";
										$add_proption.="	<td align=\"left\">\n";
										$add_proption.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
										$add_proption2 ="	</table>\n";
										$add_proption2.="	</td>\n";
										$add_proption2.="</tr>\n";

										$pattern=array("[OPT]","[OPTEND]");
										$replace=array("<tr><td>","</td></tr>");
										$proption.=$add_proption.str_replace($pattern,$replace,$proption3).$add_proption2;
									}

									echo $arproduct[$arexcel[$i]];
								} else {
									$proption ="<input type=\"hidden\" name=\"option1\">\n";
									$proption.="<input type=\"hidden\" name=\"option2\">\n";
								}
							} else if(strlen($arproduct[$arexcel[$i]])>0) {	//
								echo "<tr height=\"22\">".$arproduct[$arexcel[$i]]."</tr>\n";
								if($arexcel[$i]=="9") $dollarok="Y";
							}
						}

?>
																				</table></td>
																		</tr>
																	</table>
																	
																	<!-- ��ǰ �󼼳��� ��� ��   --></td>
															</tr>
														</table></td>
												</tr>
												<tr>
													<td height="5"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#fafafa"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#efefef"></td>
												</tr>
												<tr>
													<td height="1" bgcolor="#fafafa"></td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<?
	if(strlen($dicker)==0) {
		if($qftype == 1)
		{
			if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
				echo "<td align=\"center\"><a href=\"javascript:quickfun_CheckForm('wishlist','".$opti."')\"><IMG SRC=\"".$Dir."images/common/btn_wishlist.gif\" border=\"0\" align=\"absmiddle\"></a></td>\n";
			} else {
				echo "<td align=\"center\"><a href=\"javascript:quickfun_check_login();\"><IMG SRC=\"".$Dir."images/common/btn_wishlist.gif\" border=\"0\" align=\"absmiddle\"></a></td>\n";
			}
		}
		else
		{
			if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
			{
				if($qftype == 3)
					echo "<td align=\"center\"><a href=\"javascript:alert('ǰ�� �����̹Ƿ� �ٷα����� �� �����ϴ�.');\" onMouseOver=\"window.status='�ٷα���';return true;\"><IMG SRC=\"".$Dir."images/common/btn_directbuy.gif\" border=\"0\" align=\"middle\"></a></td>\n";
				else if($qftype == 2)
					echo "<td align=\"center\"><a href=\"javascript:alert('ǰ�� �����̹Ƿ� ��ٱ��� ���� �� �����ϴ�.');\" onMouseOver=\"window.status='��ٱ��ϴ��';return true;\"><IMG SRC=\"".$Dir."images/common/btn_basket.gif\" border=\"0\" align=\"middle\"></a></td>\n";
			}
			else
			{
				if($qftype == 3)
					echo "<td align=\"center\"><a href=\"javascript:quickfun_CheckForm('ordernow','".$opti."')\" onMouseOver=\"window.status='�ٷα���';return true;\"><IMG SRC=\"".$Dir."images/common/btn_directbuy.gif\" border=\"0\" align=\"middle\"></a></td>\n";
				else if($qftype == 2)
					echo "<td align=\"center\"><a href=\"javascript:quickfun_CheckForm('','".$opti."')\" onMouseOver=\"window.status='��ٱ��ϴ��';return true;\"><IMG SRC=\"".$Dir."images/common/btn_basket.gif\" border=\"0\" align=\"middle\"></a></td>\n";
			}
		}
	}
?>
												</tr>
											</table>
											<!-- ����   �� --> 
										</div></td>
									<td style="width:10px;background: url(/<?=RootPath?>images/common/layeropenbg_middle_right.gif) repeat-y;"></td>
								</tr>
							</table></td>
					</tr>
					<tr>
						<td id="layerbox-bottom"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
								<col width="10">
									</col>
								
								<col width="">
									</col>
								
								<col width="10">
									</col>
								
								<tr>
									<td style="width:10px;height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_left.gif) no-repeat 0 0;"></td>
									<td style="height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_center.gif)" align="right"></td>
									<td style="width:10px;height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_right.gif) no-repeat 0 0;"></td>
								</tr>
							</table></td>
					</tr>
				</table></td>
		</tr>
		<?
if(strlen($optcode)==0) {
	$maxnum=($count2-1)*10;
	if($optioncnt>0) {
		for($i=0;$i<$maxnum;$i++) {
			if ($i!=0) $quickfun_num .= ",";
			if(strlen($optioncnt[$i])==0) $quickfun_num .= "100000";
			else $quickfun_num .= $optioncnt[$i];
		}
	}

	if($priceindex>0) $quickfun_price = number_format($_pdata->sellprice)."|".number_format($_pdata->sellprice)."|";
	for($i=0; $i<$priceindex; $i++) {
		if($i!=0)
			$quickfun_price .= "|";
		$quickfun_price .= $pricetok[$i];
	}
}
?>
	</table>
	<?=$quickfun_price?>
	<script type="text/javascript">
	
		quickfun_setform.quickfun_miniq.value="<?=($miniq>1?$miniq:1)?>";
		quickfun_setform.quickfun_num.value="<?=$quickfun_num?>";
		quickfun_setform.quickfun_dicker.value="<?=(int)@strlen($dicker)?>";
		quickfun_setform.quickfun_price.value="<?=$quickfun_price?>";
		quickfun_setform.quickfun_priceindex.value="<?=$priceindex?>";
		quickfun_setform.quickfun_login.value="<?=$Dir.FrontDir?>login.php?chUrl=";
		quickfun_setform.quickfun_login2.value="<?=urlencode("?".getenv("QUERY_STRING"))?>";
	
	
		Drag.init($("layerbox-top"),$("create_openwin"));
	
	</script> 

</form>
