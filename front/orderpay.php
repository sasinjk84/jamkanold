<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

$script="window.close();opener.ProcessWait('hidden');";

$ip = getenv("REMOTE_ADDR");

$sslchecktype="";
if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
	$sslchecktype="ssl";
}
if($sslchecktype=="ssl") {
	$secure_data=getSecureKeyData($_POST["sessid"]);
	if(!is_array($secure_data)) {
		echo "<html><head><title></title></head><body onload=\"alert('�������� ������ �߸��Ǿ����ϴ�.');opener.document.form1.process.value='N';".$script."\"></body></html>";exit;
	}
	foreach($secure_data as $key=>$val) {
		${$key}=$val;
	}
} else {
	$coupon_code=$_POST["coupon_code"];
	$usereserve=$_POST["usereserve"];
	$email=$_POST["email"];
	$mobile_num1=$_POST["mobile_num1"];
	$mobile_num=$_POST["mobile_num"];
	$address=$_POST["address"];
}
$email=ereg_replace("'","",strip_tags($email));
$address=ereg_replace("'","",strip_tags($address));

$address=" ".$address;	//������ ��۷� ���ϱ�����....

if (strlen($usereserve)>0 && !IsNumeric($usereserve)) {
	echo "<html></head><body onload=\"alert('�������� ���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');opener.document.form1.process.value='N';".$script."\"></body></html>";
	exit;
}

$card_splittype=$_data->card_splittype;
$coupon_ok=$_data->coupon_ok;
$card_miniprice=$_data->card_miniprice;
$reserve_limit=$_data->reserve_limit;
$reserve_maxprice=$_data->reserve_maxprice;
if($reserve_limit==0) $reserve_limit=1000000000000;
if($_data->rcall_type=="Y") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="N") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="M") {
	$rcall_type="Y";
	$bankreserve="N";
} else {
	$rcall_type="N";
	$bankreserve="N";
}

if($_data->reserve_useadd==-1) $reserve_useadd="N";
else if($_data->reserve_useadd==-2) $reserve_useadd="U";
else $reserve_useadd=$_data->reserve_useadd;

$etcmessage=explode("=",$_data->order_msg);

if($bankreserve=="N" && $usereserve>0) {
	$bankonly="R";
}

$paylistnum="0,1,2,3,4,5";
//0:������, 1:�ſ�ī��, 2:�ǽð�������ü, 3:�������, 4:����ũ��, 5:�ڵ���

$user_reserve=0;
$reserve_type="N";
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$user_reserve = $row->reserve;
		$group_code=$row->group_code;
		mysql_free_result($result);

		if(strlen($group_code)>0 && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='".$group_code."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$group_code=$row->group_code;
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2);
				$group_usemoney=$row->group_usemoney;
				$group_addmoney=$row->group_addmoney;
				$group_payment=$row->group_payment;
			}
			mysql_free_result($result);
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� ȸ�� ���̵� �������� �ʽ��ϴ�.');opener.document.form1.process.value='N';".$script."\"></body></html>";
		exit;
	}
}

$basketsql2 = "SELECT a.productcode,a.package_idx,a.quantity,c.package_list,c.package_title,c.package_price ";
$basketsql2.= "FROM tblbasket AS a, tblproduct AS b, tblproductpackage AS c ";
$basketsql2.= "WHERE a.productcode=b.productcode ";
$basketsql2.= "AND b.package_num=c.num ";
$basketsql2.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
$basketsql2.= "AND a.package_idx>0 ";
$basketsql2.= "AND b.display = 'Y' ";

$basketresult2 = mysql_query($basketsql2,get_db_conn());
while($basketrow2=@mysql_fetch_object($basketresult2)) {
	if(strlen($basketrow2->package_title)>0 && strlen($basketrow2->package_idx)>0 && $basketrow2->package_idx>0) {
		$package_title_exp = explode("",$basketrow2->package_title);
		$package_price_exp = explode("",$basketrow2->package_price);
		$package_list_exp = explode("", $basketrow2->package_list);

		$title_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx] = $package_title_exp[$basketrow2->package_idx];

		if(strlen($package_list_exp[$basketrow2->package_idx])>1) {
			$basketsql3 = "SELECT productcode,quantity,productname,tinyimage,sellprice FROM tblproduct ";
			$basketsql3.= "WHERE pridx IN ('".str_replace(",","','",$package_list_exp[$basketrow2->package_idx])."') ";
			$basketsql3.= "AND display = 'Y' ";

			$basketresult3 = mysql_query($basketsql3,get_db_conn());
			$sellprice_package_listtmp=0;
			while($basketrow3=@mysql_fetch_object($basketresult3)) {
				$assemble_proquantity[$basketrow3->productcode]+=$basketrow2->quantity;
				$productcode_package_listtmp[] = $basketrow3->productcode;
				$quantity_package_listtmp[] = $basketrow3->quantity;
				$productname_package_listtmp[] = $basketrow3->productname;
				$tinyimage_package_listtmp[] = $basketrow3->tinyimage;
				$sellprice_package_listtmp+= $basketrow3->sellprice;
			}
			@mysql_free_result($basketresult3);

			if(count($productcode_package_listtmp)>0) {  //��ٱ��� ��Ű�� ��ǰ ���� ��½� �ʿ��� ����
				$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=0;
				if((int)$sellprice_package_listtmp>0) {
					$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=(int)$sellprice_package_listtmp;
					if(strlen($package_price_exp[$basketrow2->package_idx])>0) {
						$package_price_expexp = explode(",",$package_price_exp[$basketrow2->package_idx]);
						if(strlen($package_price_expexp[0])>0 && $package_price_expexp[0]>0) {
							$sumsellpricecal=0;
							if($package_price_expexp[1]=="Y") {
								$sumsellpricecal = ((int)$sellprice_package_listtmp*$package_price_expexp[0])/100;
							} else {
								$sumsellpricecal = $package_price_expexp[0];
							}
							if($sumsellpricecal>0) {
								if($package_price_expexp[2]=="Y") {
									$sumsellpricecal = $sellprice_package_listtmp-$sumsellpricecal;
								} else {
									$sumsellpricecal = $sellprice_package_listtmp+$sumsellpricecal;
								}
								if($sumsellpricecal>0) {
									if($package_price_expexp[4]=="F") {
										$sumsellpricecal = floor($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else if($package_price_expexp[4]=="R") {
										$sumsellpricecal = round($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else {
										$sumsellpricecal = ceil($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									}
									$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=$sumsellpricecal;
								}
							}
						}
					}
				}

				$productcode_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productcode_package_listtmp;
				$productname_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productname_package_listtmp;
				$tinyimage_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $tinyimage_package_listtmp;
			}

			unset($productcode_package_listtmp);
			unset($quantity_package_listtmp);
			unset($productname_package_listtmp);
		}
	}
}
@mysql_free_result($basketresult2);

//������ �ľ�
$errmsg="";
$sql = "SELECT a.quantity as sumquantity,b.productcode,b.productname,b.display,b.quantity,b.group_check, ";
$sql.= "b.option_quantity,b.etctype,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode ";
$result=mysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=mysql_fetch_object($result)) {
	if($row->display!="Y") {
		$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
	}
	if($row->group_check!="N") {
		if(strlen($_ShopInfo->getMemid())>0) {
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
			$sqlgc.= "WHERE productcode='".$row->productcode."' ";
			$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
			$resultgc=mysql_query($sqlgc,get_db_conn());
			if($rowgc=@mysql_fetch_object($resultgc)) {
				if($rowgc->groupcheck_count<1) {
					$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
				}
				@mysql_free_result($resultgc);
			} else {
				$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
			}
		} else {
			$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
		}
	}

	$assemble_list_exp = array();
	if(strlen($errmsg)==0 && $row->assembleuse=="Y") { // ����/�ڵ� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(strlen($row->assemble_list)==0) {
			$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		} else {
			$assemble_list_exp = explode("",$row->basketassemble_list);
		}
	}

	$package_productcode_tmp = array();
	$package_quantity_tmp = array();
	$package_productname_tmp = array();
	if(strlen($errmsg)==0 && $row->package_idx>0) { // ��Ű�� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(count($productcode_package_list[$row->productcode][$row->package_idx])>0) {
			$package_productcode_tmp = $productcode_package_list[$row->productcode][$row->package_idx];
			$package_quantity_tmp = $quantity_package_list[$row->productcode][$row->package_idx];
			$package_productname_tmp = $productname_package_list[$row->productcode][$row->package_idx];
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
			$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		}
	}

	if(strlen($errmsg)==0) {
		if ($miniq!=1 && $miniq>1 && $row->sumquantity<$miniq)
			$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ּ� ".$miniq."�� �̻� �ֹ��ϼž� �մϴ�.\\n";

		if ($maxq!="?" && $maxq>0 && $row->sumquantity>$maxq)
			$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ִ� ".$maxq."�� ���Ϸ� �ֹ��ϼž� �մϴ�.\\n";

		if(strlen($row->quantity)>0) {
			if ($row->sumquantity>$row->quantity) {
				if ($row->quantity>0)
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
				else
					$errmsg.= "[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
			}
		}
		if($assemble_proquantity_cnt==0) { //�Ϲ� �� ������ǰ���� ��� ��������
			///////////////////////////////// �ڵ�/���� ������� ���� ��� üũ ///////////////////////////////////////////////
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket ";
			$basketsql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$basketresult = mysql_query($basketsql,get_db_conn());
			while($basketrow=@mysql_fetch_object($basketresult)) {
				if($basketrow->assemble_idx>0) {
					if(strlen($basketrow->assemble_list)>0) {
						$assembleprolistexp = explode("",$basketrow->assemble_list);
						for($i=0; $i<count($assembleprolistexp); $i++) {
							if(strlen($assembleprolistexp[$i])>0) {
								$assemble_proquantity[$assembleprolistexp[$i]]+=$basketrow->quantity;
							}
						}
					}
				} else {
					$assemble_proquantity[$basketrow->productcode]+=$basketrow->quantity;
				}
			}
			@mysql_free_result($basketresult);
			$assemble_proquantity_cnt++;
		}
		if(count($assemble_list_exp)>0) { // ������ǰ�� ��� üũ
			$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
			$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
			$assemprosql.= "AND display = 'Y' ";
			$assemproresult=mysql_query($assemprosql,get_db_conn());
			while($assemprorow=@mysql_fetch_object($assemproresult)) {
				if(strlen($assemprorow->quantity)>0) {
					if($assemble_proquantity[$assemprorow->productcode]>$assemprorow->quantity) {
						if($assemprorow->quantity>0) {
							$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$assemprorow->quantity." �� �Դϴ�.")."\\n";
						} else {
							$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
						}
					}
				}
			}
		} else if(strlen($package_productcode_tmp)>0) { // ��Ű�� ������ǰ�� ��� üũ
			$package_productcode_tmpexp = explode("",$package_productcode_tmp);
			$package_quantity_tmpexp = explode("",$package_quantity_tmp);
			$package_productname_tmpexp = explode("",$package_productname_tmp);
			for($i=0; $i<count($package_productcode_tmpexp); $i++) {
				if(strlen($package_productcode_tmpexp[$i])>0) {
					if(strlen($package_quantity_tmpexp[$i])>0) {
						if($assemble_proquantity[$package_productcode_tmpexp[$i]] > $package_quantity_tmpexp[$i]) {
							if($package_quantity_tmpexp[$i]>0) {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".ereg_replace("'","",$package_productname_tmpexp[$i])."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$package_quantity_tmpexp[$i]." �� �Դϴ�.")."\\n";
							} else {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".ereg_replace("'","",$package_productname_tmpexp[$i])."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
							}
						}
					}
				}
			}
		} else { // �Ϲݻ�ǰ�� ��� üũ
			if(strlen($row->quantity)>0) {
				if($assemble_proquantity[$assemprorow->productcode]>$row->quantity) {
					if ($row->quantity>0) {
						$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
					} else {
						$errmsg.= "[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
					}
				}
			}
		}
		if(strlen($row->option_quantity)>0) {
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket ";
			$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='".$row->productcode."' ";
			$result2=mysql_query($sql,get_db_conn());
			while($row2=mysql_fetch_object($result2)) {
				$optioncnt = explode(",",substr($row->option_quantity,1));
				$optionvalue=$optioncnt[($row2->opt2_idx==0?0:($row2->opt2_idx-1))*10+($row2->opt1_idx-1)];

				if($optionvalue<=0 && $optionvalue!="") {
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
				} else if($optionvalue<$row2->quantity && $optionvalue!="") {
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
				}
			}
			mysql_free_result($result2);
		}
	}
}
mysql_free_result($result);

if(strlen($errmsg)>0) {
	echo "<html></head><body onload=\"alert('".$errmsg."');".$script."\"></body></html>";
	exit;
}
//������ �ľ� ��

$sql = "SELECT b.vender FROM tblbasket a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode GROUP BY b.vender ";
$res=mysql_query($sql,get_db_conn());

$sumprice=0;
$reserve=0;
$deli_price=0;

$optcnt=0;
$count=0;
$setquotacnt = 0;
$basketcnt=array();
$prcode=array();
$arrvender=array();
unset($prprice);
$orderpatten = array("(')","(\\\\)");
$orderreplace = array("","");
while($vgrp=mysql_fetch_object($res)) {
	//1. vender�� 0�� �ƴϸ� �ش� ������ü�� ��ۺ� �߰� �������� �����´�.
	unset($_vender);
	if($vgrp->vender>0) {
		$sql = "SELECT deli_price,deli_pricetype,deli_mini,deli_area,deli_limit,deli_area_limit FROM tblvenderinfo WHERE vender='".$vgrp->vender."' ";
		$res2=mysql_query($sql,get_db_conn());
		if($_vender=mysql_fetch_object($res2)) {
			if($_vender->deli_price==-9) {
				$_vender->deli_price=0;
				$_vender->deli_after="Y";
			}
			if ($_vender->deli_mini==0) $_vender->deli_mini=1000000000;
		}
		mysql_free_result($res2);
	}

	$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,a.date,b.productcode,b.productname,b.sellprice, ";
	$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
	$sql.= "b.etctype,b.deli_price,b.deli,b.sellprice*a.quantity as realprice,a.assemble_list,a.assemble_idx,a.package_idx ";
	$sql.= "FROM tblbasket a, tblproduct b ";
	$sql.= "WHERE b.vender='".$vgrp->vender."' ";
	$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode ";
	$result=mysql_query($sql,get_db_conn());

	$vender_sumprice = 0;	//�ش� ������ü�� �� ���ž�
	$vender_delisumprice = 0;//�ش� ������ü�� �⺻��ۺ� �� ���ž�
	$vender_deliprice = 0;
	$deli_productprice=0;
	$deli_init = false;
	while($row = mysql_fetch_object($result)) {
		if(strlen($prcode[0])>0) {
			if(substr($row->productcode,0,12)==substr($prcode[0],0,12)) $prcode[0]=substr($prcode[0],0,12);
			else if(substr($row->productcode,0,9)==substr($prcode[0],0,9)) $prcode[0]=substr($prcode[0],0,9);
			else if(substr($row->productcode,0,6)==substr($prcode[0],0,6)) $prcode[0]=substr($prcode[0],0,6);
			else if(substr($row->productcode,0,3)==substr($prcode[0],0,3)) $prcode[0]=substr($prcode[0],0,3);
			else $prcode[0]="";
		}
		if((int)$basketcnt[0]==0) $prcode[0]=$row->productcode;

		if($vgrp->vender>0) {
			if(strlen($prcode[$vgrp->vender])>0) {
				if(substr($row->productcode,0,12)==substr($prcode[$vgrp->vender],0,12)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,12);
				else if(substr($row->productcode,0,9)==substr($prcode[$vgrp->vender],0,9)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,9);
				else if(substr($row->productcode,0,6)==substr($prcode[$vgrp->vender],0,6)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,6);
				else if(substr($row->productcode,0,3)==substr($prcode[$vgrp->vender],0,3)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,3);
				else $prcode[$vgrp->vender]="";
			}
			if((int)$basketcnt[$vgrp->vender]==0) $prcode[$vgrp->vender]=$row->productcode;
		}

		$optvalue2[$count]="";
		if(ereg("^(\[OPTG)([0-9]{4})(\])$",$row->option1)) {
			$optioncode = substr($row->option1,5,4);
			$row->option_price="";
			if($row->optidxs!="") {
				$tempoptcode = substr($row->optidxs,0,-1);
				$exoptcode = explode(",",$tempoptcode);
				$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
				$resultopt = mysql_query($sqlopt,get_db_conn());
				if($rowopt = mysql_fetch_object($resultopt)){
					$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
					$opti=0;
					$optvalue[$count]="";
					while(strlen($optionadd[$opti])>0) {
						if($exoptcode[$opti]>0) {
							$opval = explode("",str_replace('"','',$optionadd[$opti]));
							$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
							$optvalue[$count].= ", ".$opval[0]." : ";
							if ($exop[1]>0) $optvalue[$count].=$exop[0]."(<font color=#FF3C00>+".$exop[1]."��</font>)";
							else if($exop[1]==0) $optvalue[$count].=$exop[0];
							else $optvalue[$count].=$exop[0]."(<font color=#FF3C00>".$exop[1]."��</font>)";
							$row->sellprice+=$exop[1];
						}
						$opti++;
					}
					$optvalue[$count] = substr($optvalue[$count],1);
					$optcnt++;

					$optvalue2[$count] = "[OPTG".substr("00".$optcnt,-3)."]";
				}
			}
		}

		$productcode[$count]=$row->productcode;
		$option_quantity[$productcode[$count]]=$row->option_quantity;
		$option1num[$count]=$row->opt1_idx;
		$option2num[$count]=($row->opt2_idx>0?$row->opt2_idx:1);
		$productname[$count]=preg_replace($orderpatten,$orderreplace,$row->productname);
		$addcode[$count]=preg_replace($orderpatten,$orderreplace,$row->addcode);
		$quantity[$count]=$row->quantity;

		#####################��ǰ�� ȸ�������� ���� ����#######################################
		$dSql = "SELECT * FROM tblmemberdiscount WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
		if(false !== $dResult = mysql_query($dSql,get_db_conn())){
			if(mysql_num_rows($dResult)){
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				
				if($discountprices >0 AND $dRow->discountYN == 'Y'){
					if($discountprices < 1){
						$row->sellprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$row->sellprice = $row->sellprice - $discountprices;
					}
				}	
			}
		}
		#####################��ǰ�� ȸ�������� ���� �� #######################################

		if($row->assemble_idx>0 && strlen(str_replace("","",$row->assemble_list))>0) {
			$assemble_list_proexp = explode("",$row->assemble_list);
			$alprosql = "SELECT SUM(sellprice) AS assemble_sellprice FROM tblproduct ";
			$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
			$alprosql.= "AND display = 'Y' ";
			$alproresult=mysql_query($alprosql,get_db_conn());
			$alprorow=@mysql_fetch_object($alproresult);

			//######### �ڵ�/������ ���� ���� ���� üũ ###############
			$price = $alprorow->assemble_sellprice;
			$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$alprorow->assemble_sellprice,"N");
		} else if($row->package_idx>0 && strlen($row->package_idx)>0) {
			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (strlen($row->option_price)==0) {
				$price = $row->sellprice+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
			}
		} else {
			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (strlen($row->option_price)==0) {
				$price = $row->sellprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
			}
		}
		#####################��ǰ�� ȸ�������� ���� ����#######################################
		if($dRow->over_discount=="Y"){//�ߺ����� ������ �����հ�
			$sale_sumprice += $price;
		}
		#####################��ǰ�� ȸ�������� ���� �� #######################################

		$realreserve[$count]=$tempreserve;
		//######### �ɼǿ� ���� ���� ���� üũ �� ############
		$sumprice += $price*$row->quantity;
		$vender_sumprice += $price*$row->quantity;
		$reserve += $tempreserve*$row->quantity;

		$arrvender[0]["sumprice"]+=$price*$row->quantity;
		if($vgrp->vender>0) {
			$arrvender[$vgrp->vender]["sumprice"]+=$price*$row->quantity;
		}

		if ($row->opt1_idx>0) {
			$temp = $row->option1;
			$tok = explode(",",$temp);
			$option1[$count]=$tok[0]." : ".$tok[$row->opt1_idx];
			$option1[$count]=ereg_replace("'","",$option1[$count]);
		}  // if
		if ($row->opt2_idx>0) {
			$temp = $row->option2;
			$tok = explode(",",$temp);
			$option2[$count]=$tok[0]." : ".$tok[$row->opt2_idx];
			$option2[$count]=ereg_replace("'","",$option2[$count]);
		}  // if
		if(strlen($optvalue2[$count])>0) $option1[$count]=$optvalue2[$count];
		$date[$count]=$row->date;
		$realprice[$count]=$price;

		########### ���� ���� ###############
		$prprice[0][$row->productcode]=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,3)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,6)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,9)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,12)]+=$price*$row->quantity;
		if($vgrp->vender>0) {
			$prprice[$vgrp->vender][$row->productcode]=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,3)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,6)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,9)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,12)]+=$price*$row->quantity;
		}

		//######## Ư����üũ : ���ݰ�����ǰ//�����ڻ�ǰ #####
		if (strlen($row->etctype)>0) {
			$etctemp = explode("|",$row->etctype);
			for ($i=0;$i<count($etctemp);$i++) {
				switch ($etctemp[$i]) {
					case "BANKONLY":
						$bankonly = "Y";
						break;
					case "SETQUOTA":
						if ($card_splittype=="O" && $sumprice>=$card_splitprice) {
							$setquotacnt++;
						}
						break;
				}
			}
		}

		//################ ���� ��ۺ� üũ #################
		if (($row->deli=="Y" || $row->deli=="N") && $row->deli_price>0) {
			if($row->deli=="Y") {
				$deli_productprice += $row->deli_price*$row->quantity;
			} else {
				$deli_productprice += $row->deli_price;
			}
		} else if($row->deli=="F" || $row->deli=="G") {
			$deli_productprice += 0;
		} else {
			$deli_init=true;
			$vender_delisumprice += $price*$row->quantity;
		}

		$basketcnt[0]++;
		if($vgrp->vender>0) $basketcnt[$vgrp->vender]++;

		$count++;
	}
	mysql_free_result($result);

	$deli_area="";
	$vender_deliprice=$deli_productprice;
	$vender_deliarealimit_init=false;
	if($_vender) {
		if(strlen($_vender->deli_area_limit)>0) {
			if($_vender->deli_pricetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_vender->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(strlen(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)));

						if(strlen($vender_deliarealimit)>0) {
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit;
							break;
						}
					}
				}
				if(strlen($vender_deliarealimit)>0) {
					break;
				}
			}
		}

		if($vender_deliarealimit_init==false){
			if($_vender->deli_price>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_vender->deli_mini && $deli_init==true) {
					$vender_deliprice+=$_vender->deli_price;
				}
			} else if(strlen($_vender->deli_limit)>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}
				if($deli_init==true) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_vender->deli_limit);
					$vender_deliprice+=$delilmitprice;
				}
			}
		}
		$deli_area=$_vender->deli_area;
	} else {
		if(strlen($_data->deli_area_limit)>0) {
			if($_data->deli_basefeetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_data->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(strlen(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)));

						if(strlen($vender_deliarealimit)>0) {
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit;
							break;
						}
					}
				}
				if(strlen($vender_deliarealimit)>0) {
					break;
				}
			}
		}

		if($vender_deliarealimit_init==false){
			if($_data->deli_basefee>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_data->deli_miniprice && $deli_init==true) {
					$vender_deliprice+=$_data->deli_basefee;
				}
			} else if(strlen($_data->deli_limit)>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if($deli_init==true) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_data->deli_limit);
					$vender_deliprice+=$delilmitprice;
				}
			}
		}
		$deli_area=$_data->deli_area;
	}

	//������ ��۷Ḧ ����Ѵ�.
	$area_price=0;
	unset($array_deli);
	$array_deli = explode("|",$deli_area);
	$cnt2= floor(count($array_deli)/2);
	for($kk=0;$kk<$cnt2;$kk++){
		$subdeli=explode(",",$array_deli[$kk*2]);
		for($jj=0;$jj<count($subdeli);$jj++){
			if(strlen(trim($subdeli[$jj]))>0 && strpos($address,$subdeli[$jj])>0) {
				$area_price=$array_deli[$kk*2+1];
			}
		}
	}

	$vender_deliprice+=$area_price;
	$deli_price+=$vender_deliprice;
}
mysql_free_result($res);

// ��ü��ǰ(basketcnt)�� �����ڼ��û�ǰ(setquotacnt)�� ���� �����������̰�����ǰ���� ���õǾ� ������
if ($basketcnt[0]==$setquotacnt && $setquotacnt>0 && $card_splittype=="O") $card_splittype="Y";

if($reserve_limit<0) $reserve_limit=(int)($sumprice*abs($reserve_limit)/100);

$usereserve = ereg_replace(",","",$usereserve);

if ($usereserve>0) {
	if($reserve_maxprice>$sumprice)
		$usereserve=0;
	else if($user_reserve>=$_data->reserve_maxuse && $usereserve<=$reserve_limit && $usereserve<=$user_reserve) {
		$reserve_type="Y";
	} else $usereserve=0;
} else $usereserve=0;

if($_data->coupon_ok=="Y" && strlen($coupon_code)==8 && $rcall_type=="N" && $usereserve>0) {
	$usereserve=0;
}

if($sumprice<$_data->bank_miniprice) {
	echo "<html></head><body onload=\"alert('�ֹ� ������ �ּ� �ݾ��� ".number_format($_data->bank_miniprice)."�� �Դϴ�.');".$script."\"></body></html>";
	exit;
} else if($sumprice<=0) {
	echo "<html></head><body onload=\"alert('��ǰ �� ������ 0���� ��� ��ǰ �ֹ��� ���� �ʽ��ϴ�.');".$script."\"></body></html>";
	exit;
}
if($_data->ETCTYPE["VATUSE"]=="Y") {
	$sumpricevat = return_vat($sumprice);
}
$banksumprice=$cardsumprice=$essumprice=$sumprice;
if($sumprice>0) {
	$salemoney=0;
	$salereserve=0;
	//if(strlen($group_type)>0 && $group_type!=NULL && $sumprice>=$group_usemoney) {	//�׷� ���� ����
	if(strlen($group_type)>0 && $group_type!=NULL && $sale_sumprice>=$group_usemoney) {	//�׷� ���� ����
		if($group_type=="SW" || $group_type=="SP") {
			if($group_type=="SW") {
				$salemoney=$group_addmoney;
			} else if($group_type=="SP") {
				//$salemoney=substr(((int)($sumprice*($group_addmoney/100))),0,-2)."00";
				$salemoney=substr(((int)($sale_sumprice*($group_addmoney/100))),0,-2)."00";
			}
			if(preg_match("/^(B|N)$/",$group_payment)) $bankdc_price="-".$salemoney;
			if(preg_match("/^(C|N)$/",$group_payment)) $carddc_price="-".$salemoney;
			if(preg_match("/^(N)$/",$group_payment)) $esdc_price="-".$salemoney;
		}
	}

	if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && strlen($coupon_code)==8) {
		$date = date("YmdH");
		$sql = "SELECT a.coupon_code, a.coupon_name, a.sale_type, a.sale_money, a.bank_only, a.productcode, ";
		$sql.= "a.mini_price,a.use_con_type1,a.use_con_type2,a.use_point,a.vender, b.date_start, b.date_end ";
		$sql.= "FROM tblcouponinfo a, tblcouponissue b ";
		$sql.= "WHERE b.id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND a.coupon_code=b.coupon_code AND b.date_start<='".$date."' ";
		$sql.= "AND (b.date_end>='".$date."' OR b.date_end='') ";
		$sql.= "AND a.coupon_code='".$coupon_code."' AND b.used='N' ";
		$resultcou = mysql_query($sql,get_db_conn());
		if($rowcou=mysql_fetch_object($resultcou)) {
			$codeA=substr($rowcou->productcode,0,3);
			$codeB=substr($rowcou->productcode,3,3);
			$codeC=substr($rowcou->productcode,6,3);
			$codeD=substr($rowcou->productcode,9,3);

			$likecode=$codeA;
			if($codeB!="000") $likecode.=$codeB;
			if($codeC!="000") $likecode.=$codeC;
			if($codeD!="000") $likecode.=$codeD;

			if($prcode[$rowcou->vender]=="") $prcode[$rowcou->vender]="ALL";  // ��ü��ǰ
			else {
				$prleng=strlen($rowcou->productcode);

				if($prleng==18) $rowcou->productcode=$rowcou->productcode;
				else $rowcou->productcode=$likecode;

				$num = strlen($tempprcode);
				$prcode[$rowcou->vender] = substr($prcode[$rowcou->vender],0,$num);
			}

			//$rowcou->productcode=$likecode;

			if(($rowcou->mini_price==0 || $rowcou->mini_price<=$arrvender[$rowcou->vender]["sumprice"])  // �ѱ��űݾ��� �����ݾ׺��� ū�� �˻�
			&& ($rowcou->use_con_type2=="Y" && ($rowcou->productcode==$prcode[$rowcou->vender] || $rowcou->productcode=="ALL" || ($rowcou->use_con_type1=="Y" && $rowcou->productcode!="ALL"))
			|| ($rowcou->use_con_type2=="N" && (($rowcou->use_con_type1=="Y" && $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode]>0) || ($rowcou->use_con_type1=="N" && strlen($prprice[$rowcou->vender][$rowcou->productcode])==0))))
			){
				if($rowcou->productcode=="ALL") $couponmoney = $arrvender[$rowcou->vender]["sumprice"];
				else if($rowcou->use_con_type2=="N")  $couponmoney = $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode];
				else $couponmoney = $prprice[$rowcou->vender][$rowcou->productcode];

				$tempcoumoney = floor(($rowcou->sale_type<=2?($couponmoney/100*$rowcou->sale_money):($couponmoney>0?$rowcou->sale_money:0))/pow(10,$rowcou->amount_floor))*pow(10,$rowcou->amount_floor);
				if($rowcou->sale_type%2==0) {      // �ֹ��ݾ�����
					$coumoney = -$tempcoumoney;
					$coureserve=0;
					if($rowcou->bank_only=="Y") {
						$banksumprice=$banksumprice + $coumoney;
					} else {
						$banksumprice=$banksumprice + $coumoney;
						$cardsumprice=$cardsumprice + $coumoney;
						$essumprice=$essumprice + $coumoney;
					}
				}
				// �׷�������뿩��
				if($rowcou->use_point!="Y") {
					$bankdc_price=$carddc_price=$esdc_price="";
				}
			}
		}
		mysql_free_result($resultcou);
	}
}

//���� �� �ݾ�
if(strlen($carddc_price)>0) $cardsumprice= $cardsumprice - $salemoney;
if(strlen($bankdc_price)>0) $banksumprice= $banksumprice - $salemoney;
if(strlen($esdc_price)>0) $essumprice= $essumprice - $salemoney;

$cardsumprice+=$deli_price;
$banksumprice+=$deli_price;
$essumprice+=$deli_price;

if($_data->ETCTYPE["VATUSE"]=="Y") {
	$banksumprice+=$sumpricevat;
	$cardsumprice+=$sumpricevat;
	$essumprice+=$sumpricevat;
}

if ($_data->card_payfee>0) {  // ī������� �߰� ������ ����
	$cardsumprice = ((int) ($cardsumprice * (1+$_data->card_payfee/100) /100)) * 100;
} else if ($_data->card_payfee<0 && $sumprice>$usereserve) {
	if($_data->card_payfee<-50){
		$_data->card_payfee+=50;
		$saletype="Y";
	}
	$_data->card_payfee=abs($_data->card_payfee);
	$dctemp = floor(($banksumprice-$deli_price)/100*$_data->card_payfee/100)*100;
	if($saletype!="Y") $banksumprice=$banksumprice-$dctemp;
	else if($saletype=="Y") {
		$bankremess="�� <font color=red><u>ȸ���� ���� ���� ������ �����ݾ��� ".$_data->card_payfee."%�� <b>".number_format($dctemp)."��</b>�� �߰������� �帳�ϴ�.</u></font>";
		$bankremess1="<br>".$bankremess;
		$bankremess2=$bankremess."<br>";
		$_data->card_payfee=0;
	}
}

$banklast_price = $banksumprice - $usereserve;
$cardlast_price = $cardsumprice - $usereserve;
$eslast_price = $essumprice - $usereserve;

if ($banklast_price<0) $banklast_price=0;
if ($cardlast_price<0) $cardlast_price=0;
if ($eslast_price<0) $eslast_price=0;

$escrow_info = GetEscrowType($_data->escrow_info);
if(strlen($_data->escrow_id)>0 && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (strlen($escrow_info["onlycard"])==0 && strlen($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

if ($escrow_info["nopayment"]=="Y" && strlen($_data->card_id)==0 && (int)$banklast_price>=$escrow_info["escrow_limit"]) {
	echo "<html></head><body onload=\"alert('".number_format($escrow_info["escrow_limit"])."�� �̻� ������ �������� �ʽ��ϴ�.\\n\\n�����ݾ��� �����Ͽ� �ֹ��Ͻñ� �ٶ��ϴ�.');opener.document.form1.process.value='N';".$script."\"></body></html>";
	exit;
}
//esbank => Y:������ �Աݰ� ������ ����/������ �������� ����.
if ($escrow_info["esbank"]!="Y") $eslast_price=$banklast_price;

$smsok="N";
$sql="SELECT COUNT(*) as cnt FROM tblsmsinfo WHERE mem_bank='Y'";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
if($row->cnt==1){
	$smsok="Y";
}
mysql_free_result($result);

/*
echo "sumprice => $sumprice<br>";
echo "banksumprice => $banksumprice<br>";
echo "cardsumprice => $cardsumprice<br>";
echo "essumprice => $essumprice<br><br>";

echo "banklast_price => $banklast_price<br>";
echo "cardlast_price => $cardlast_price<br>";
echo "eslast_price => $eslast_price<br><br>";

exit;
*/
?>

<html>
<head>
<title>����</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.resizeTo(594,600);
function CheckForm() {
	try {
		if (document.form1.paymethod.length==null) {
			if(document.form1.paymethod.checked==false) {
				alert("��������� �����ϼ���.");
				opener.document.form1.paymethod.value="";
				return;
			}
			opener.document.form1.paymethod.value=document.form1.paymethod.value;
		} else {
			var ispaymentcheck=false;
			for(i=0;i<document.form1.paymethod.length;i++) {
				if(document.form1.paymethod[i].checked==true) {
					opener.document.form1.paymethod.value=document.form1.paymethod[i].value;
					ispaymentcheck=true;
					break;
				}
			}
			if(ispaymentcheck==false) {
				alert("��������� �����ϼ���.");
				opener.document.form1.paymethod.value="";
				return;
			}
		}
	} catch(e) {
		return;
	}

	if(opener.document.form1.paymethod.value=="B" && document.form1.pay_data1.selectedIndex!=0) {
		opener.document.form1.pay_data1.value=document.form1.pay_data1.options[document.form1.pay_data1.selectedIndex].value;
	} else if(opener.document.form1.paymethod.value=="B" && document.form1.pay_data1.selectedIndex<=0) {
		alert("�Աݰ��¸� �����ϼ���.");
		opener.document.form1.paymethod.value="";
		document.form1.pay_data1.focus();
		return;
	}

	<? if($bankonly=="R") { ?>
	if(opener.document.form1.paymethod.value=="C" || opener.document.form1.paymethod.value=="M"){
		if(!confirm("�����Աݽÿ��� ������ ����� �����մϴ�.\n\n������ ��� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	<? } ?>

	if(opener.document.form1.paymethod.value=="V") {
		if(typeof(document.form1.sender_resno1)=="object" && typeof(document.form1.sender_resno2)=="object") {
			if (document.form1.sender_resno1.value.length==0) {
				alert("���¼����� �ֹε�Ϲ�ȣ�� �Է��ϼ���.");
				document.form1.sender_resno1.focus();
				return;
			}
			if (document.form1.sender_resno2.value.length==0) {
				alert("���¼����� �ֹε�Ϲ�ȣ�� �Է��ϼ���.");
				document.form1.sender_resno2.focus();
				return;
			}

			var bb;
			bb = chkResNo(document.form1.sender_resno1.value+"-"+document.form1.sender_resno2.value);

			if (!bb) {
				alert("���¼������� �ֹε�Ϲ�ȣ�� �߸��Ǿ����ϴ�.\n\n�ٽ� �Է��ϼ���");
				document.form1.sender_resno1.focus();
				return;
			}
			opener.document.form1.sender_resno.value=document.form1.sender_resno1.value+""+document.form1.sender_resno2.value
		}
	}

	opener.document.form1.pay_data2.value;

	opener.CheckForm();

	window.close();
}

function CheckPayment(val) {
	if(val=="V") {
		try {
			document.all["idx_trans"].style.display="";
		} catch (e) {}
	} else {
		try {
			document.all["idx_trans"].style.display="none";
		} catch (e) {}
	}
}

function bank_selected() {
	if (document.form1.paymethod.length==null) document.form1.paymethod.checked=true;
	else if (document.form1.paymethod[0].value=="B") document.form1.paymethod[0].checked=true;
}

function notuse(card_miniprice) {
	if (document.form1.paymethod.length==null)
		document.form1.paymethod.checked=false;
	else {
		for (i=0;i<document.form1.paymethod.length;i++) document.form1.paymethod[i].checked=false;
		if (document.form1.paymethod[0].value=="B") document.form1.paymethod[0].checked=true;
	}
	alert("�ֹ��ݾ��� "+card_miniprice+"�� �̻��̿��� ������ �����մϴ�.");
}

function ordercancel(gbn) {
	window.close();
	try {
		opener.ordercancel(gbn);
	} catch (e) {}
}

function strnumkeyup2(field) {
	if (!isNumber(field.value)) {
		alert("���ڸ� �Է��ϼ���.");
		field.value=strLenCnt(field.value,field.value.length - 1);
		field.focus();
		return;
	}
	if (field.name == "sender_resno1") {
		if (field.value.length == 6) {
			form1.sender_resno2.focus();
		}
	}
}

//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post>
<tr>
	<td><IMG  src="<?=$Dir?>images/common/orderpay_title.gif" border="0"></td>
</tr>
<tr>
	<td style="padding:10px;">
	<table cellpadding="0" cellspacing="0" width="100%">
<?
$arrpayinfo=explode("=",$_data->bank_account);
$arrcardcom=array("A"=>"[<font color=red>KCP.CO.KR</font>]","B"=>"[<font color=red>dacompay.net (���������ڻ�ŷ�)</font>]","C"=>"[<font color=red>allthegate.com (�ô�����Ʈ)</font>]","D"=>"[<font color=red>inicis.com (�̴Ͻý�)</font>]");
$cardid_info=GetEscrowType($_data->card_id);

//������
if($escrow_info["onlycard"]!="Y" || (int)$banklast_price<$escrow_info["escrow_limit"]) {
	if(preg_match("/^(Y|N)$/", $_data->payment_type)) {//��������� ������ OR �¶��ΰ����� ���õǾ��� ���
		$pmethodlist[0].= "<tr>\n";
		$pmethodlist[0].= "	<td>\n";
		$pmethodlist[0].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$pmethodlist[0].= "	<tr>\n";
		$pmethodlist[0].= "		<td>\n";
		$pmethodlist[0].= "		<input type=radio name=\"paymethod\" value=\"B\" onclick=\"CheckPayment(this.value)\"><img src=\"".$Dir."images/common/orderpay_icon1.gif\" border=\"0\" align=\"absmiddle\"> <font color=\"#F02800\"><b>�����ݾ� : ".number_format($banklast_price)."��</b></font>\n";
		$pmethodlist[0].= "		<select name=\"pay_data1\" onchange=\"bank_selected()\" style=\"font-size:11px;font-family:'����,����';background-color:#404040;letter-spacing:-0.5pt;\">\n";
		if(strlen($arrpayinfo[1])==0) $pmethodlist[0].= "			<option value=\"\" style=\"color:#ffffff;\">�Ա� ���¹�ȣ ���� (�ݵ�� �ֹ��� �������� �Ա�)</option>\n";
		else $pmethodlist[0].= "			<option value=\"\" style=\"color:#ffffff;\">".$arrpayinfo[1]."</option>\n";
		$count=0;
		if (strlen($arrpayinfo[0])>0) {
			$tok = strtok($arrpayinfo[0],",");
			$count = 0;
			while ($tok) {
				$pmethodlist[0].="			<option value=\"".$tok."\" style=\"color:#ffffff;\">".$tok."</option>\n";
				$tok = strtok(",");
				$count++;
			}
		}
		$pmethodlist[0].= "		</select>\n";
		$pmethodlist[0].= "		</td>\n";
		$pmethodlist[0].= "	</tr>\n";
		$pmethodlist[0].= "	<tr>\n";
		$pmethodlist[0].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
		$pmethodlist[0].= "		".$bankremess2;
		$pmethodlist[0].= "		- <font color=\"#FF0000\">�Ա�Ȯ����</font>, �����ϰ� ������ ��ǰ�� ����մϴ�.";
		if($smsok=="Y") {
			$pmethodlist[0].="<br>- �Ա� ���¹�ȣ �� �ݾ��� ���� �ڵ������� �߼��� �帳�ϴ�!";
		}
		$pmethodlist[0].= "		</font></td>";
		$pmethodlist[0].= "	</tr>\n";
		$pmethodlist[0].= "	</table>\n";
		$pmethodlist[0].= "	</td>\n";
		$pmethodlist[0].= "</tr>\n";
	}
}

//2:�ſ�ī��
if(preg_match("/^(Y|C)$/", $_data->payment_type) && strlen($_data->card_id)>0) {
	$pmethodlist[1].= "<tr>\n";
	$pmethodlist[1].= "	<td>\n";
	$pmethodlist[1].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$pmethodlist[0].= "	<tr>\n";
	$pmethodlist[1].= "		<td>\n";
	if($_data->card_miniprice!=0 && $_data->card_miniprice>$cardlast_price) {
		$pmethodlist[1].= "		<input type=radio name=\"paymethod\" value=\"C\" onclick=\"CheckPayment(this.value);notuse('".number_format($_data->card_miniprice)."')\"><img src=\"".$Dir."images/common/orderpay_icon2.gif\" border=\"0\" align=\"absmiddle\"> - <font color=\"#F02800\"><b>ī����� �ּ� �ֹ� �ݾ� : ".number_format($_data->card_miniprice)."��</b></font>\n";
	} else {
		$pmethodlist[1].= "		<input type=radio name=\"paymethod\" value=\"C\"".(($_data->payment_type=="C" && $bankonly!="Y")?"checked":"").($bankonly=="Y"?"onclick=\"this.checked=false;CheckPayment(this.value);alert('���ݰ��� ��ǰ�� �ֱ� ������ ������ �Ա� ������ �����Ͻ� �� �ֽ��ϴ�.');\"":"onclick=\"CheckPayment(this.value)\"")."><img src=\"".$Dir."images/common/orderpay_icon2.gif\" border=\"0\" align=\"absmiddle\"> \n";
		if($_data->card_payfee>0) {
			$pmethodlist[1].="<font color=\"#F02800\"><b>".(strlen($arrpayinfo[2])==0?"�������ǸŰ�":$arrpayinfo[2])." : ".number_format($cardlast_price)."��</b></font><font style=\"font-size:11px;letter-spacing:-0.5pt;\"> �� ī�������, [�������ΰ�] ������ �ȵ˴ϴ�.</font>";
		} else {
			$pmethodlist[1].="<font color=\"#F02800\"><b>�����ݾ� : ".number_format($cardlast_price)."��</b></font>";
		}
	}
	$pmethodlist[1].= "		</td>\n";
	$pmethodlist[1].= "	</tr>\n";
	$pmethodlist[1].= "	<tr>\n";
	$pmethodlist[1].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
	$pmethodlist[1].= "		- �ſ�ī�� ������ ������ ���� ������, <font color=\"#FF0000\">128bit SSL</font>�� ��ȣȭ�� ����â�� ���� ��ϴ�!";
	$pmethodlist[1].= "		<br>- ������, ī������� ".$arrcardcom[$cardid_info["PG"]]."�� ǥ�õ˴ϴ�!";
	$pmethodlist[1].= "		</font></td>";
	$pmethodlist[1].= "	</tr>\n";
	$pmethodlist[1].= "	<input type=hidden name=\"pay_data2\" value=\"�ſ�ī����� - ī���ۼ���\">\n";
	$pmethodlist[1].= "	</table>\n";
	$pmethodlist[1].= "	</td>\n";
	$pmethodlist[1].= "</tr>\n";
}

//2:�ǽð�������ü
if($escrow_info["onlycard"]!="Y" || (int)$banklast_price<$escrow_info["escrow_limit"]) {
	if(strlen($_data->trans_id)>0) {
		$pmethodlist[2].= "<tr>\n";
		$pmethodlist[2].= "	<td>\n";
		$pmethodlist[2].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$pmethodlist[2].= "	<tr>\n";
		$pmethodlist[2].= "		<td>\n";
		$pmethodlist[2].= "		<input type=radio name=\"paymethod\" value=\"V\" ".($cardid_info["PG"]=="C"?"":" onclick=\"CheckPayment(this.value)\"")."><img src=\"".$Dir."images/common/orderpay_icon3.gif\" border=\"0\" align=\"absmiddle\"> <font color=\"#F02800\"><b>�����ݾ� : ".number_format($banklast_price)."��</b></font>\n";
		$pmethodlist[2].= "		</td>\n";
		$pmethodlist[2].= "	</tr>\n";
		if($cardid_info["PG"]!="C") {
		$pmethodlist[2].= "	<tr id=\"idx_trans\" style=\"display:none\">\n";
		$pmethodlist[2].= "		<td style=\"padding-left:29\"><font color=\"#F02800\"><B>���¼����� �ֹι�ȣ :</B></font> <input type=text name=sender_resno1 size=6 class=input onkeyup=\"strnumkeyup2(this)\">-<input type=text name=sender_resno2 size=7 class=input onkeyup=\"strnumkeyup2(this)\"></td>\n";
		$pmethodlist[2].= "	</tr>\n";
		}
		$pmethodlist[2].= "	<tr>\n";
		$pmethodlist[2].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
		$pmethodlist[2].= "		".$bankremess2;
		$pmethodlist[2].= "		- ���� ���� �����Է����� �����ݾ��� ��ü�Ǵ� �����Դϴ�.<br>";
		$pmethodlist[2].= "		- ���ͳݹ�ŷ�� ������ ���ȹ���� �����ϹǷ� �����ϸ�, ������ ������ ���� �ʽ��ϴ�.";
		$pmethodlist[2].= "		</font></td>";
		$pmethodlist[2].= "	</tr>\n";
		$pmethodlist[2].= "	</table>\n";
		$pmethodlist[2].= "	</td>\n";
		$pmethodlist[2].= "</tr>\n";
	}
}

//3:�������
if($escrow_info["onlycard"]!="Y" || (int)$banklast_price<$escrow_info["escrow_limit"]) {
	if(strlen($_data->virtual_id)>0) {
		$pmethodlist[3].= "<tr>\n";
		$pmethodlist[3].= "	<td>\n";
		$pmethodlist[3].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$pmethodlist[3].= "	<tr>\n";
		$pmethodlist[3].= "		<td>\n";
		$pmethodlist[3].= "		<input type=radio name=\"paymethod\" value=\"O\" onclick=\"CheckPayment(this.value)\"><img src=\"".$Dir."images/common/orderpay_icon4.gif\" border=\"0\" align=\"absmiddle\"> <font color=\"#F02800\"><b>�����ݾ� : ".number_format($banklast_price)."��</b></font>\n";
		$pmethodlist[3].= "		</td>\n";
		$pmethodlist[3].= "	</tr>\n";
		$pmethodlist[3].= "	<tr>\n";
		$pmethodlist[3].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
		$pmethodlist[3].= "		".$bankremess2;
		$pmethodlist[3].= "		- <font color=\"#FF0000\">����!</font> 1ȸ�� ����(�������) �Աݽ�, <font color=\"#FF0000\">�̸�/�ݾ���</font> �� ��ġ�ؾ� �Ա�Ȯ�ε˴ϴ�!";
		$pmethodlist[3].= "		</font></td>";
		$pmethodlist[3].= "	</tr>\n";
		$pmethodlist[3].= "	</table>\n";
		$pmethodlist[3].= "	</td>\n";
		$pmethodlist[3].= "</tr>\n";
	}
}

//4:����ũ��
if(($escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)$banklast_price>=$escrow_info["escrow_limit"])) && strlen($_data->escrow_id)>0) {
	if ($escrow_info["percent"]>0) {  // ����ũ�� ������ �߰� ������
		$estemplast_price = ((int) ($eslast_price * ($escrow_info["percent"]/100) /10)) * 10;
		if($estemplast_price<300) $estemplast_price=300;
		$eslast_price+=$estemplast_price;
	}
	$pgid_info="";
	$pg_type="";
	$pgid_info=GetEscrowType($_data->escrow_id);
	$pg_type=trim($pgid_info["PG"]);

	if(preg_match("/^(A|B|C|D)$/",$pg_type)) {
		//KCP/������/�ô�����Ʈ/�̴Ͻý� ������� ����ũ�� �ڵ�
		$pmethodlist[4].= "<tr>\n";
		$pmethodlist[4].= "	<td>\n";
		$pmethodlist[4].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$pmethodlist[4].= "	<tr>\n";
		$pmethodlist[4].= "		<td>\n";
		$pmethodlist[4].= "		<input type=radio name=\"paymethod\" value=\"Q\" onclick=\"CheckPayment(this.value)\"><img src=\"".$Dir."images/common/orderpay_icon6.gif\" border=\"0\" align=\"absmiddle\"> ";
		if ($escrow_info["percent"]>0){
			$pmethodlist[4].="<font color=\"#F02800\"><b>������ ���԰� : ".number_format($eslast_price)."��</b></font></font>";
		} else {
			$pmethodlist[4].="<font color=\"#F02800\"><b>�����ݾ� : ".number_format($eslast_price)."��</b></font>";
		}
		$pmethodlist[4].= "		</td>\n";
		$pmethodlist[4].= "	</tr>\n";
		$pmethodlist[4].= "	<tr>\n";
		$pmethodlist[4].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
		$pmethodlist[4].= "		".$bankremess2;
		$pmethodlist[4].= "		- <font color=\"#FF0000\">����!</font> 1ȸ�� ����(�������) �Աݽ�, <font color=\"#FF0000\">�̸�/�ݾ���</font> �� ��ġ�ؾ� �Ա�Ȯ�ε˴ϴ�!<br>";
		$pmethodlist[4].= "		- ����ũ�θ� ���ؼ� ���Ű����� �Ͻ� �� �ִ� ��������Դϴ�.\n";
		$pmethodlist[4].= "		</font></td>";
		$pmethodlist[4].= "	</tr>\n";
		$pmethodlist[4].= "	</table>\n";
		$pmethodlist[4].= "	</td>\n";
		$pmethodlist[4].= "</tr>\n";
	}
}

//5:�ڵ���
if(strlen($_data->mobile_id)>0) {
	$pmethodlist[5].= "<tr>\n";
	$pmethodlist[5].= "	<td>\n";
	$pmethodlist[5].= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$pmethodlist[5].= "	<tr>\n";
	$pmethodlist[5].= "		<td>\n";
	$pmethodlist[5].= "		<input type=radio name=\"paymethod\" value=\"M\" ".($bankonly=="Y"?"onclick=\"this.checked=false;CheckPayment(this.value);alert('���ݰ��� ��ǰ�� �ֱ� ������ ������ �Ա� ������ �����Ͻ� �� �ֽ��ϴ�.');\"":"onclick=\"CheckPayment(this.value)\"")." ><img src=\"".$Dir."images/common/orderpay_icon5.gif\" border=\"0\" align=\"absmiddle\"> \n";
	if($_data->card_payfee>0) {
		$pmethodlist[5].="<font color=\"#F02800\"><b>".(strlen($arrpayinfo[2])==0?"�������ǸŰ�":$arrpayinfo[2])." : ".number_format($cardlast_price)."��</b></font><font style=\"font-size:11px;letter-spacing:-0.5pt;\"> �� �ڵ���������, [�������ΰ�] ������ �ȵ˴ϴ�.</font>";
	} else {
		$pmethodlist[5].="<font color=\"#F02800\"><b>�����ݾ� : ".number_format($cardlast_price)."��</b></font>";
	}
	$pmethodlist[5].= "		</td>\n";
	$pmethodlist[5].= "	</tr>\n";
	$pmethodlist[5].= "	<tr>\n";
	$pmethodlist[5].= "		<td style=\"padding-left:21px;\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><font color=\"#999999\">";
	$pmethodlist[5].= "		- ���������� ������ ���� ������, ���� ����� ����â�� ���� ��ϴ�!<br>";
	$pmethodlist[5].= "		- ������, �ڵ��� ���û������ ";
	if(preg_match("/^(A)$/",$pg_type)) {
		$pmethodlist[5].= "'�Ҿװ���'";
	} else if(preg_match("/^(C)$/",$pg_type)) {
		$pmethodlist[5].= "'(��)�ٳ�'";
	} else if(preg_match("/^(D)$/",$pg_type)) {
		$pmethodlist[5].= "'�������/�������'";
	} else {
		$pmethodlist[5].= "'(��)�������'";
	}
	$pmethodlist[5].= "		�� ǥ�õ˴ϴ�!";
	$pmethodlist[5].= "		</font></td>";
	$pmethodlist[5].= "	</tr>\n";
	$pmethodlist[5].= "	<input type=hidden name=\"pay_data2\" value=\"�ڵ������� - �ۼ���\">\n";
	$pmethodlist[5].= "	</table>\n";
	$pmethodlist[5].= "	</td>\n";
	$pmethodlist[5].= "</tr>\n";
}

	$arlist = explode(",",$paylistnum);
	$arcnt = count($arlist);
	$cnt=0;
	for($i=0;$i<$arcnt;$i++) {
		if(strlen($pmethodlist[$arlist[$i]])>0) {
			echo $pmethodlist[$arlist[$i]];
			echo "<tr>\n";
			echo "	<td height=\"25\"><hr size=\"1\" noshade color=\"#F3F3F3\" width=\"100%\"></td>\n";
			echo "</tr>\n";

			$cnt++;
		}
	}
?>
	<tr>
		<td valign="bottom" align="center"><a href="javascript:CheckForm()" onMouseOver="window.status='�ֹ��Ϸ�';return true;"><img src="<?=$Dir?>images/common/orderpay_btnok.gif" border="0"></a><a href="javascript:ordercancel('cancel')" onMouseOver="window.status='�ֹ����';return true;"><img src="<?=$Dir?>images/common/orderpay_btncancel.gif" border="0" hspace="5"></a></td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table>
</body>
</html>