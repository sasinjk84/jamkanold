<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

$ip = getenv("REMOTE_ADDR");

$sslchecktype="";
if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
	$sslchecktype="ssl";
}
if($sslchecktype=="ssl") {
	$secure_data=getSecureKeyData($_POST["sessid"]);
	if(!is_array($secure_data)) {
		echo "<html><head><title></title></head><body onload=\"alert('�������� ������ �߸��Ǿ����ϴ�.');history.go(-2);\"></body></html>";exit;
	}
	foreach($secure_data as $key=>$val) {
		${$key}=$val;
	}
} else {
	foreach($_POST as $key=>$val) {
		${$key}=$val;
	}
}

$sender_name=ereg_replace(" ","",$sender_name);
$sender_email=ereg_replace("'","",$sender_email);
$receiver_name=ereg_replace(" ","",$receiver_name);
$order_msg=ereg_replace("'","",$order_msg);
$sender_tel=ereg_replace("'","",$sender_tel);
$receiver_tel1=ereg_replace("'","",$receiver_tel1);
$receiver_tel2=ereg_replace("'","",$receiver_tel2);
$receiver_addr=ereg_replace("'","",$receiver_addr);
$rpost=$rpost1.$rpost2;
$loc=substr($raddr1,0,4);
$receiver_email=ereg_replace("'","",$receiver_email);
$receiver_message=ereg_replace("'","",$receiver_message);

if (strlen($paymethod)==0) {
	echo "<html></head><body onload=\"alert('��������� ���õ��� �ʾҽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if (strlen($usereserve)>0 && !IsNumeric($usereserve)) {
	echo "<html></head><body onload=\"alert('�������� ���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if(strlen($_data->escrow_id)==0 && $paymethod=="Q") {
	echo "<html></head><body onload=\"alert('����ũ�� ������ �������� �ʽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

$escrow_info = GetEscrowType($_data->escrow_info);
if(strlen($_data->escrow_id)>0 && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (strlen($escrow_info["onlycard"])==0 && strlen($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

$pg_type="";
switch ($paymethod) {
	case "B":
		break;
	case "V":
		$pgid_info=GetEscrowType($_data->trans_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "O":
		$pgid_info=GetEscrowType($_data->virtual_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "Q":
		$pgid_info=GetEscrowType($_data->escrow_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "C":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "P":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "M":
		$pgid_info=GetEscrowType($_data->mobile_id);
		$pg_type=$pgid_info["PG"];
		break;
}
$pg_type=trim($pg_type);

$pmethod=$paymethod.$pg_type;

if ($paymethod!="B" && strlen($pg_type)==0) {
	echo "<html></head><body onload=\"alert('�����Ͻ� ��������� �̿��Ͻ� �� �����ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

$card_splittype=$_data->card_splittype;
$card_splitmonth=$_data->card_splitmonth;
$card_splitprice=$_data->card_splitprice;

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

#�������� ���ݰ����ÿ��� ��밡���ϰ� ���ݰ����� ���þ�������
if($bankreserve=="N" && !preg_match("/^(B|V|O|Q)$/",$paymethod)) {
	$usereserve=0;
}

$user_reserve=0;
$reserve_type="N";
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$ordercode=unique_id();
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
		$_ShopInfo->SetMemNULL();
		//guest
		$ordercode=unique_id()."X";
		$id="X".date("iHs").$sender_name;
	}
} else {
	//guest
	$ordercode=unique_id()."X";
	$id="X".date("iHs").$sender_name;
}

$basketsql2 = "SELECT a.productcode,a.package_idx,a.quantity,c.package_list,c.package_title,c.package_price ";
$basketsql2.= "FROM tblbasket3 AS a, tblproduct AS b, tblproductpackage AS c ";
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
$sql = "SELECT a.quantity as sumquantity,b.productcode,b.productname,b.display,b.quantity,b.group_check,b.social_chk ";
$sql.= "b.option_quantity,b.etctype,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket3 a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode ";
$result=mysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=mysql_fetch_object($result)) {
	if($row->display!="Y") {
		$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
	}
	if($row->social_chk =="Y") {	//�ҼȻ�ǰ
		$sql2 = "SELECT count(1) as cnt FROM tblproduct_social WHERE pcode='".$row->productcode."' AND '".time()."' between sell_startdate and sell_enddate ";
		$result2=mysql_query($sql2,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			if(strlen($errmsg)==0 && $row2->cnt == 0){
				$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ����� ��ǰ�Դϴ�.\\n";
			}
		}
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
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket3 ";
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
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket3 ";
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
	echo "<html></head><body onload=\"alert('".$errmsg."');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
}

$sql = "SELECT b.vender FROM tblbasket3 a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
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
unset($prname);
$orderpatten = array("(')","(\\\\)");
$orderreplace = array("","");
$goodname="";
$allprname="";
$arr_deliprice=array();
$arr_delimsg=array();
$arr_delisubj=array();

$address=" ".$raddr1;
$address=ereg_replace("'","",strip_tags($address));
$address=" ".$address;	//������ ��۷� ���ϱ�����....

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
	$sql.= "b.etctype,b.deli_price,b.deli,b.sellprice*a.quantity as realprice, b.selfcode, b.bisinesscode,a.assemble_list,a.assemble_idx,a.package_idx ";
	$sql.= ", a.basketidx, b.sns_state,b.present_state,b.pester_state,b.sns_reserve2,b.sns_reserve2_type, a.sell_memid "; //sns �� ��Ÿ �߰���� ����
	$sql.= "FROM tblbasket3 a, tblproduct b ";
	$sql.= "WHERE b.vender='".$vgrp->vender."' ";
	$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode ";
	$sql.= "ORDER BY a.date DESC ";
	$result=mysql_query($sql,get_db_conn());

	$vender_sumprice = 0;	//�ش� ������ü�� �� ���ž�
	$vender_delisumprice = 0;//�ش� ������ü�� �⺻��ۺ� �� ���ž�
	$vender_deliprice = 0;
	$deli_productprice=0;
	$deli_productname1="";
	$deli_productname2="";
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
		$vender[$count]=$vgrp->vender;
		$selfcode[$count]=$row->selfcode;
		$bisinesscode[$count]=$row->bisinesscode;
		$assemble_idx[$count]=$row->assemble_idx;
		$assemble_info[$count]="";
		$assemble_productcode[$count]="";
		$package_idx[$count]=$row->package_idx;
		$package_info[$count]="";
		$package_productcode[$count]="";
		$sell_memid[$count]=$row->sell_memid;

		if(strlen($row->bisinesscode)>0) {
			$bisinessvalue[$row->bisinesscode]=$row->bisinesscode;
		}

		if($msg_type=="2") {
			$ordermessage[$count]=${"order_prmsg".$count};
		} else {
			$ordermessage[$count]=$order_prmsg;
		}
		$ordermessage[$count]=ereg_replace("'","",$ordermessage[$count]);

		if($row->assemble_idx>0 && strlen(str_replace("","",$row->assemble_list))>0) {
			$assemble_list_proexp = explode("",$row->assemble_list);
			$alprosql = "SELECT productcode,productname,sellprice FROM tblproduct ";
			$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
			$alprosql.= "AND display = 'Y' ";
			$alproresult=mysql_query($alprosql,get_db_conn());

			$assemble_productcode_imp=array();
			$assemble_productname_imp=array();
			$assemble_sellprice_imp=array();
			$assemble_sellerprice=0;
			while($alprorow=@mysql_fetch_object($alproresult)) {
				$assemble_sellerprice+=$alprorow->sellprice;
				$assemble_productcode_imp[]=$alprorow->productcode;
				$assemble_productname_imp[]=$alprorow->productname;
				$assemble_sellprice_imp[]=(int)$alprorow->sellprice;
			}
			if(count($assemble_productcode_imp)>0) {
				$assemble_info[$count] = preg_replace($orderpatten,$orderreplace,implode("",$assemble_productcode_imp).":".implode("",$assemble_productname_imp).":".implode("",$assemble_sellprice_imp));
				$assemble_productcode[$count]=implode("",$assemble_productcode_imp);
			}
			@mysql_free_result($alproresult);

			//######### �ڵ�/������ ���� ���� ���� üũ ###############
			$price = $assemble_sellerprice;
			$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$assemble_sellerprice,"N");
			//snsȫ���� ��� ������
			if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $row->sns_state == "Y" && $row->sell_memid !=""){
				$tempreserve = getReserveConversionSNS($tempreserve,$row->sns_reserve2,$row->sns_reserve2_type,$assemble_sellerprice,"N");
			}
		} else if($row->package_idx>0 && strlen($row->package_idx)>0) {
			$package_info[$count] = preg_replace($orderpatten,$orderreplace,implode("",$productcode_package_list[$row->productcode][$row->package_idx]).":".implode("",$productname_package_list[$row->productcode][$row->package_idx]).":".$price_package_listtmp[$row->productcode][$row->package_idx]).":".$title_package_listtmp[$row->productcode][$row->package_idx];
			$package_productcode[$count]=implode("",$productcode_package_list[$row->productcode][$row->package_idx]);

			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (strlen($row->option_price)==0) {
				$price = $row->sellprice+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
				//snsȫ���� ��� ������
				if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $row->sns_state == "Y" && $row->sell_memid !=""){
					$tempreserve = getReserveConversionSNS($tempreserve,$row->sns_reserve2,$row->sns_reserve2_type,$price,"N");
				}
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
				//snsȫ���� ��� ������
				if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $row->sns_state == "Y" && $row->sell_memid !=""){
					$tempreserve = getReserveConversionSNS($tempreserve,$row->sns_reserve2,$row->sns_reserve2_type,$price,"N");
				}
			}
		} else {
			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (strlen($row->option_price)==0) {
				$price = $row->sellprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
				//snsȫ���� ��� ������
				if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $row->sns_state == "Y" && $row->sell_memid !=""){
					$tempreserve = getReserveConversionSNS($tempreserve,$row->sns_reserve2,$row->sns_reserve2_type,$row->sellprice,"N");
				}
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
				//snsȫ���� ��� ������
				if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $row->sns_state == "Y" && $row->sell_memid !=""){
					$tempreserve = getReserveConversionSNS($tempreserve,$row->sns_reserve2,$row->sns_reserve2_type,$pricetok[$row->opt1_idx-1],"N");
				}
			}
		}
		$realreserve[$count]=$tempreserve;

		if (strlen($goodname)>0) $goodname = $row->productname." ��.."; else $goodname = $row->productname;

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

		$prname[0][$row->productcode]=$row->productname.", ";
		$prname[0][substr($row->productcode,0,3)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,6)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,9)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,12)].=$row->productname.", ";
		if($vgrp->vender>0) {
			$prprice[$vgrp->vender][$row->productcode]=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,3)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,6)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,9)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,12)]+=$price*$row->quantity;

			$prname[$vgrp->vender][$row->productcode]=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,3)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,6)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,9)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,12)].=$row->productname.", ";
		}


		$allprname.=$row->productname.", ";

		//######## Ư����üũ : ���ݰ�����ǰ//�����ڻ�ǰ #####
		if (strlen($row->etctype)>0) {
			$etctemp = explode("",$row->etctype);
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
				$deli_productname2.=$row->productname.", ";
			} else {
				$deli_productprice += $row->deli_price;
				$deli_productname2.=$row->productname.", ";
			}
		} else if($row->deli=="F" || $row->deli=="G") {
			$deli_productprice += 0;
			if($row->deli=="F") {
				$deli_productname2.=$row->productname.", ";
			} else {
				$deli_productname2.=$row->productname.", ";
			}
		} else {
			$deli_init=true;
			$vender_delisumprice += $price*$row->quantity;
		}
		$deli_productname1.=$row->productname.", ";

		$basketcnt[0]++;
		if($vgrp->vender>0) $basketcnt[$vgrp->vender]++;
		$count++;
	}
	mysql_free_result($result);

	$deli_area="";
	$deli_productname="";
	$vender_deliprice=$deli_productprice;
	if($deli_productprice>0) {
		$deli_productname=$deli_productname2;
	}

	$vender_deliarealimit_init=false;
	if($_vender) {
		$arr_delisubj[$vgrp->vender]="";
		if(strlen($_vender->deli_area_limit)>0) {
			if($_vender->deli_pricetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$vender_deliarealimit_exp = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_vender->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(strlen(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)),"Y");
						if(strlen($vender_deliarealimit)>0) {
							$vender_deliarealimit_exp = explode("", $vender_deliarealimit);
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit_exp[0];
							$arr_delisubj[$vgrp->vender]="�ش� ����� ".$deli_area_limit_exp2[$jj]."�̰� ��ǰ �����հ谡 ".$vender_deliarealimit_exp[1]."�� ���";
							break;
						}
					}
				}
				if(strlen($vender_deliarealimit_exp[0])>0) {
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
					$deli_productname=$deli_productname1;

					if($_vender->deli_mini<1000000000) {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".number_format($_vender->deli_mini)."�� �̸��� ���";
					} else {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ ���Ž� ������ û��";
					}
				}
			} else if(strlen($_vender->deli_limit)>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}
				if($deli_init==true) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_vender->deli_limit,"Y");
					$delilmitprice_exp = explode("", $delilmitprice);
					$vender_deliprice+=$delilmitprice_exp[0];
					$deli_productname=$deli_productname1;

					$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".$delilmitprice_exp[1]."�� ���";
				}
			}
		}
		$deli_area=$_vender->deli_area;
	} else {
		$arr_delisubj[$vgrp->vender]="";
		if(strlen($_data->deli_area_limit)>0) {
			if($_data->deli_basefeetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$vender_deliarealimit_exp = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_data->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(strlen(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)),"Y");

						if(strlen($vender_deliarealimit)>0) {
							$vender_deliarealimit_exp = explode("", $vender_deliarealimit);
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit_exp[0];
							$arr_delisubj[$vgrp->vender]="�ش� ����� ".$deli_area_limit_exp2[$jj]."�̰� ��ǰ �����հ谡 ".$vender_deliarealimit_exp[1]."�� ���";
							break;
						}
					}
				}
				if(strlen($vender_deliarealimit_exp[0])>0) {
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
					$deli_productname=$deli_productname1;

					if($_data->deli_miniprice<1000000000) {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".number_format($_data->deli_miniprice)."�� �̸��� ���";
					} else {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ ���Ž� ������ û��";
					}
				}
			} else if(strlen($_data->deli_limit)>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if($deli_init==true) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_data->deli_limit,"Y");
					$delilmitprice_exp = explode("", $delilmitprice);
					$vender_deliprice+=$delilmitprice_exp[0];
					$deli_productname=$deli_productname1;

					$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".$delilmitprice_exp[1]."�� ���";
				}
			}
		}
		$deli_area=$_data->deli_area;
	}
	if($deli_productprice>0) {
		if(strlen($arr_delisubj[$vgrp->vender])>0) {
			$arr_delisubj[$vgrp->vender].=", ��ǰ ������ۺ� ����";
		} else {
			$arr_delisubj[$vgrp->vender].="��ǰ ������ۺ� ����";
		}
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

	if($area_price>0) {
		if(strlen($arr_delisubj[$vgrp->vender])>0) {
			$arr_delisubj[$vgrp->vender].=", �ش� ����� �߰���۷�";
		} else {
			$arr_delisubj[$vgrp->vender].="�ش� ����� �߰���۷�";
		}
	}


	$vender_deliprice+=$area_price;
	if($vender_deliprice>0) {
		$arr_deliprice[$vgrp->vender]=$vender_deliprice;
		$arr_delimsg[$vgrp->vender]=substr($deli_productname,0,-2);
	}
	$deli_price+=$vender_deliprice;
}
mysql_free_result($res);

if(count($bisinessvalue)>0) {
	$bisinessvalue_imp = implode("','", $bisinessvalue);
	$bisql = "SELECT companyviewval, companycode ";
	$bisql.= "FROM tblproductbisiness ";
	$bisql.= "WHERE companycode IN ('".$bisinessvalue_imp."') ";
	$biresult=mysql_query($bisql,get_db_conn());

	while($birow = mysql_fetch_object($biresult)) {
		$companyviewval[$birow->companycode] = preg_replace($orderpatten,$orderreplace,$birow->companyviewval);
	}
}
// ���ݰ�����ǰ�� �ִµ� ī��������ý�
if ($bankonly=="Y" && !preg_match("/^(B|V|O|Q)$/",$paymethod)) {
	echo "<html></head><body onload=\"alert('���ݰ��� ��ǰ�� �ֱ� ������ ������ �Ա� ������ �����Ͻ� �� �ֽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

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
	echo "<html></head><body onload=\"alert('�ֹ� ������ �ּ� �ݾ��� ".number_format($_data->bank_miniprice)."�� �Դϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
} else if($sumprice<=0) {
	echo "<html></head><body onload=\"alert('��ǰ �� ������ 0���� ��� ��ǰ �ֹ��� ���� �ʽ��ϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
}

if(preg_match("/^(C|P)$/", $paymethod)) {
	if($_data->card_miniprice>$sumprice) {
		echo "<html></head><body onload=\"alert('ī����� �ּ� �ֹ��ݾ׺��� �����ݾ��� �۽��ϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
		exit;
	}
} else if(preg_match("/^(B|V|O|Q)$/",$paymethod) && $sumprice<$_data->bank_miniprice) {
	echo "<html></head><body onload=\"alert('�ּ� �ֹ��ݾ׺��� �����ݾ��� �۽��ϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
}


if ($reserve_type=="N") $usereserve=0;

############################################# ������� �۾��Ϸ� ############################################

//�ֹ� ��ٱ��Ͽ� ��ǰ ����
for($orderi=0;$orderi<$count;$orderi++) {
	if(strlen($optvalue2[$orderi])>0){
		$optvalue2[$orderi]=str_replace("'","\'",$optvalue2[$orderi]);
		$optvalue[$orderi]=str_replace("'","\'",$optvalue[$orderi]);
		$sql = "INSERT INTO tblorderoptiontemp (ordercode, productcode, opt_idx, opt_name) VALUES ('".$ordercode."','".$productcode[$orderi]."','".$optvalue2[$orderi]."','".$optvalue[$orderi]."')";
		mysql_query($sql,get_db_conn());
		backup_save_sql($sql);
	}

	if($reserve_useadd!="N" && $usereserve>=$reserve_useadd && $usereserve!=0) $realreserve[$orderi]=0;
	else if($reserve_useadd=="U" && $usereserve!=0) {
		$reservepercent = 100 * ($sumprice-$usereserve) / $sumprice;
		$realreserve[$orderi]=round($realreserve[$orderi]*($reservepercent/100),-1);
	}

	$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, opt1_name, opt2_name, package_idx, assemble_idx, addcode, quantity, price, reserve, date, selfcode, productbisiness, order_prmsg, assemble_info,sell_memid) VALUES ('".$vender[$orderi]."','".$ordercode."','".$_ShopInfo->getTempkey()."','".$productcode[$orderi]."','".$productname[$orderi]."','".$option1[$orderi]."','".$option2[$orderi]."','".$package_idx[$orderi]."','".$assemble_idx[$orderi]."','".$addcode[$orderi]."','".$quantity[$orderi]."','".$realprice[$orderi]."','".$realreserve[$orderi]."','".$date[$orderi]."','".$selfcode[$orderi]."', '".$companyviewval[$bisinesscode[$orderi]]."','".$ordermessage[$orderi]."','".$package_info[$orderi]."=".$assemble_info[$orderi]."','".$sell_memid[$orderi]."')";
	mysql_query($sql,get_db_conn());
	backup_save_sql($sql);

	if (mysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",getenv("HTTP_HOST")."<br>$sql - ".mysql_error(),"Content-Type: text/plain\r\n");
	} else {
		$tempoptcnt="";
		if(strlen($option_quantity[$productcode[$orderi]])>0) {
			$optioncnt2 = explode(",",substr($option_quantity[$productcode[$orderi]],1));
			if($optioncnt2[($option2num[$orderi]-1)*10+($option1num[$orderi]-1)]!="") $optioncnt2[($option2num[$orderi]-1)*10+($option1num[$orderi]-1)]-=$quantity[$orderi];
			for($j=0;$j<5;$j++) {
				for($i=0;$i<10;$i++) {
					$tempoptcnt.=",".$optioncnt2[$j*10+$i];
				}
			}
			if(strlen($tempoptcnt)>0) {
				$option_quantity[$productcode[$orderi]]=$tempoptcnt.",";
				$tempoptcnt=",option_quantity='".$tempoptcnt.",'";
			}
		}

		if(strlen($assemble_productcode[$orderi])>0) {
			$assemble_productcode_exp = explode("",$assemble_productcode[$orderi]);
			for($k=0; $k<count($assemble_productcode_exp); $k++) {
				#��ǰDB�� ���� ������Ʈ
				$sql = "UPDATE tblproduct SET ";
				$sql.= "sellcount		= sellcount+1, ";
				//$sql.= "selldate		= now(), ";
				$sql.= "quantity		= quantity-".$quantity[$orderi]." ";
				$sql.= "WHERE productcode='".$assemble_productcode_exp[$k]."' ";
				mysql_query($sql,get_db_conn());
				delete_cache_file("product", "code=".substr($assemble_productcode_exp[$k],0,3));
			}
		}

		if(strlen($package_productcode[$orderi])>0) {
			$package_productcode_exp = explode("",$package_productcode[$orderi]);
			for($k=0; $k<count($package_productcode_exp); $k++) {
				#��ǰDB�� ���� ������Ʈ
				$sql = "UPDATE tblproduct SET ";
				$sql.= "sellcount		= sellcount+1, ";
				//$sql.= "selldate		= now(), ";
				$sql.= "quantity		= quantity-".$quantity[$orderi]." ";
				$sql.= "WHERE productcode='".$package_productcode_exp[$k]."' ";
				mysql_query($sql,get_db_conn());
				delete_cache_file("product", "code=".substr($package_productcode_exp[$k],0,3));
			}
		}

		#��ǰDB�� ���� ������Ʈ
		$sql = "UPDATE tblproduct SET ";
		$sql.= "sellcount		= sellcount+1, ";
		//$sql.= "selldate		= now(), ";
		$sql.= "quantity		= quantity-".$quantity[$orderi]." ";
		$sql.= $tempoptcnt." WHERE productcode='".$productcode[$orderi]."' ";
		mysql_query($sql,get_db_conn());
		delete_cache_file("product", "code=".substr($productcode[$orderi],0,3));
	}
}
delete_cache_file("main");

$oldtempkey=$_ShopInfo->getTempkey();
$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
$_ShopInfo->setGifttempkey($oldtempkey);
$_ShopInfo->setOldtempkey($oldtempkey);
$_ShopInfo->setOkpayment("");
$_ShopInfo->Save();

if ($paymethod=="B") $pay_data = $pay_data1;
else if (preg_match("/^(C|P)$/", $paymethod)) $pay_data = $pay_data2;
else if ($paymethod=="V") $pay_data = "�ǽð� ������ü ������";
if($_data->ETCTYPE["VATUSE"]=="Y") {
	$sumpricevat = return_vat($sumprice);
}
if($sumprice>0) {
	$salemoney=0;
	$salereserve=0;

	if(strlen($group_type)>0 && $group_type!=NULL && $sumprice>=$group_usemoney && ($group_payment=="N" || ($group_payment=="B" && preg_match("/^(B|V|O|Q)$/",$paymethod)) || ($group_payment=="C" && preg_match("/^(C|P)$/", $paymethod)))) {
		if($group_type=="SW" || $group_type=="SP") {
			if($group_type=="SW") {
				$salemoney=$group_addmoney;
			} else if($group_type=="SP") {
				$salemoney=substr(((int)($sumprice*($group_addmoney/100))),0,-2)."00";
			}
			$dc_price="-".$salemoney;
		}
		if($group_type=="RW" || $group_type=="RP" || $group_type=="RQ") {
			if($group_type=="RW") $salereserve=$group_addmoney;
			else if($group_type=="RP") $salereserve=$reserve*($group_addmoney-1);
			else if($group_type=="RQ") $salereserve=substr(((int)($sumprice*($group_addmoney/100))),0,-2)."00";
			$dc_price="+".$salereserve;
		}
		if($escrow_info["esbank"]=="Y" && $paymethod=="Q" && $group_payment=="B") $dc_price="";
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

				if($prleng==18) $tempprcode=$rowcou->productcode;
				else $tempprcode=$likecode;

				$num = strlen($tempprcode);
				$prcode[$rowcou->vender] = substr($prcode[$rowcou->vender],0,$num);
			}

			$rowcou->productcode=$likecode;

			if($rowcou->bank_only=="Y" && $escrow_info["esbank"]=="Y" && $paymethod=="Q") {
				$coupon_code="";
			} else if($rowcou->bank_only=="Y" && !preg_match("/^(B|V|O|Q)$/", $paymethod)) {
				$coupon_code="";
			} else if(($rowcou->mini_price==0 || $rowcou->mini_price<=$arrvender[$rowcou->vender]["sumprice"])  // �ѱ��űݾ��� �����ݾ׺��� ū�� �˻�
			&& ($rowcou->use_con_type2=="Y" && ($rowcou->productcode==$prcode[$rowcou->vender] || $rowcou->productcode=="ALL" || ($rowcou->use_con_type1=="Y" && $rowcou->productcode!="ALL"))
			|| ($rowcou->use_con_type2=="N" && (($rowcou->use_con_type1=="Y" && $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode]>0) || ($rowcou->use_con_type1=="N" && strlen($prprice[$rowcou->vender][$rowcou->productcode])==0))))
			){
				if($rowcou->productcode=="ALL") {		#����ǰ
					$couponmoney = $arrvender[$rowcou->vender]["sumprice"];
					$couponmsg=$allprname;
				} else if($rowcou->use_con_type2=="N") {#�ش��ǰ �Ǵ� ī�װ��� ������ ������ ��ǰ
					$couponmoney = $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode];
					$couponmsg=str_replace($prname[$rowcou->vender][$rowcou->productcode],"",$allprname);
				} else {								#�ش��ǰ �Ǵ� �ش� ī�װ� ����ǰ
					$couponmoney = $prprice[$rowcou->vender][$rowcou->productcode];
					$couponmsg=$prname[$rowcou->vender][$rowcou->productcode];
				}
				$couponmsg=substr($couponmsg,0,-2);

				$tempcoumoney = floor(($rowcou->sale_type<=2?($couponmoney/100*$rowcou->sale_money):($couponmoney>0?$rowcou->sale_money:0))/pow(10,$rowcou->amount_floor))*pow(10,$rowcou->amount_floor);
				//1�������� ���ֱ� ���ؼ� ����.
				if($rowcou->sale_type%2==0) {      // �ֹ��ݾ�����
					$coumoney = -$tempcoumoney;
					$coureserve=0;
					$sumprice = $sumprice + $coumoney;
				} else {
					$coumoney = 0;
					$coureserve= $tempcoumoney;
				}
				// �׷�������뿩��
				if($rowcou->use_point!="Y") {
					$dc_price="";
				}

				if(strlen($tempcoumoney)>0 && $tempcoumoney>0) {
					$coupon_name=titleCut(50,$rowcou->coupon_name)." - ".number_format($rowcou->sale_money).($rowcou->sale_type<=2?"%":"��").($rowcou->sale_type%2==0?"����":"����")."����";
					$coupon_name = addslashes($coupon_name);

					$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('".$rowcou->vender."','".$ordercode."','".$oldtempkey."','COU".$coupon_code."X','".$coupon_name."','1','".$coumoney."','".$coureserve."','".date("Ymd")."','".$couponmsg."')";
					mysql_query($sql,get_db_conn());
					backup_save_sql($sql);

					$sql = "UPDATE tblcouponissue SET used='Y' WHERE id='".$_ShopInfo->getMemid()."' AND coupon_code='".$coupon_code."' ";
					mysql_query($sql,get_db_conn());
				}
			}
		}
		mysql_free_result($resultcou);
	}
}

if(strlen($dc_price)>0) $sumprice= $sumprice - $salemoney;
$sumprice+=$deli_price;
if($_data->ETCTYPE["VATUSE"]=="Y") {
	if($sumpricevat>0) {
		$sumprice+=$sumpricevat;
		$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999997X','�ΰ��� VAT 10% �ΰ�','1','".$sumpricevat."','0','".date("Ymd")."')";
		mysql_query($sql,get_db_conn());
		backup_save_sql($sql);
	}
}
if (preg_match("/^(C|P|M)$/", $paymethod) && $_data->card_payfee>0) {  // ī������� �߰� ������ ����
	$tempprice = ((int) ($sumprice * ($_data->card_payfee/100) /100)) * 100;
	$sumprice+=$tempprice;
	$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999998X','ī������� �ݾ׿��� ".$_data->card_payfee."% ������ �ΰ�','1','".$tempprice."','0','".date("Ymd")."')";
	mysql_query($sql,get_db_conn());
	backup_save_sql($sql);
} else if (preg_match("/^(B|V|O|Q)$/",$paymethod) && $_data->card_payfee<0 && $sumprice>$usereserve) {
	// ���ݰ����� ������ ���� & �����ݾ׸����� ����������
	if($paymethod=="Q" && $escrow_info["esbank"]=="Y") {
		;
	} else {
		if($_data->card_payfee<-50){
			$_data->card_payfee+=50;
			$saletype="Y";
		}
		$_data->card_payfee=abs($_data->card_payfee);
		$dctemp = floor(($sumprice-$deli_price)/100*$_data->card_payfee/100)*100;
		if($saletype=="Y" && strlen($_ShopInfo->getMemid())>0) {
			$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999999X','���ݰ����� �����ݾ׿��� ".$_data->card_payfee."% �߰� ����','1','0','".$dctemp."','".date("Ymd")."')";
			mysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		} else if($saletype!="Y") {
			$sumprice = $sumprice - $dctemp;
			$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999999X','���ݰ����� �����ݾ׿��� ".$_data->card_payfee."% �߰� ����','1',".-$dctemp.",'0','".date("Ymd")."')";
			mysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		}
	}
}

$last_price = $sumprice - $usereserve;

if ($paymethod=="Q" && $escrow_info["percent"]>0) {  // ����ũ�� ������ �߰� ������ ����
	$templast_price = ((int) ($last_price * ($escrow_info["percent"]/100) /10)) * 10;
	if($templast_price<300) $templast_price=300;
	$last_price+=$templast_price;
	$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999998X','����ũ�� ������ �ݾ׿��� ".$escrow_info["percent"]."% ������ �ΰ�','1','".$templast_price."','0','".date("Ymd")."')";
	mysql_query($sql,get_db_conn());
	backup_save_sql($sql);
}

//��ü�� ��۷� tblorderproducttemp ���̺� insert
if(count($arr_deliprice)>0) {
	while(list($key,$val)=each($arr_deliprice)) {
		if($val>0) {
			$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('".$key."','".$ordercode."','".$oldtempkey."','99999999990X','��۷�(".$arr_delisubj[$key].")','1','".$val."','0','".date("Ymd")."','".$arr_delimsg[$key]."')";
			mysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		}
	}
}

if(strlen($_ShopInfo->getMemid())==0) {
	$sql = "INSERT INTO tblorderinfotemp (ordercode, tempkey, id, price, deli_price, paymethod, ";
	$sql.= "pay_data, sender_name, sender_email, sender_tel, receiver_name, receiver_tel1, receiver_tel2, ";
	$sql.= "receiver_addr, order_msg, ip, del_gbn, partner_id, loc, order_type, receiver_email, receiver_message, gift) VALUES (";
	$sql.= "'".$ordercode."', '".$oldtempkey."', '".$id."', '".$last_price."', ";
	$sql.= "'".$deli_price."', '".$pmethod."', '".$pay_data."', '".$sender_name."', '".$sender_email."', ";
	$sql.= "'".$sender_tel."', '".$receiver_name."', '".$receiver_tel1."', '".$receiver_tel2."', ";
	$sql.= "'".$receiver_addr."', '".$order_msg."', '".$ip."', '', '".$_ShopInfo->getRefurl()."', '".$loc."', '".$ordertype."', '".$receiver_email."', '".$receiver_message."', '3')";
	mysql_query($sql,get_db_conn());
	backup_save_sql($sql);
	if (mysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",getenv("HTTP_HOST")."<br>$sql - ".mysql_error(),"Content-Type: text/plain\r\n");
	}
} else {
	if($sumprice<=$usereserve) {
		$remain_reserve = $user_reserve - $sumprice;
		$usereserve = $sumprice;
	} else {
		$remain_reserve=$user_reserve-$usereserve;
	}
	if ($last_price<0) $last_price=0;

	if( $remain_reserve < 0 ) $remain_reserve = 0;

	$sql = "INSERT INTO tblorderinfotemp (ordercode, tempkey, id, price, deli_price, dc_price, ";
	$sql.= "reserve, paymethod, pay_data, ";
	if($last_price==0) {
		$pay_data="�� ���űݾ� ".number_format($usereserve)."���� ���������� ����";
		$sql.= "bank_date, ";
		if(preg_match("/^(O|Q)$/", $paymethod)) $sql.= "pay_flag, ";	//������¸�,,,
	}
	$sql.= "sender_name, sender_email, sender_tel, receiver_name, receiver_tel1, receiver_tel2, ";
	$sql.= "receiver_addr, order_msg, ip, del_gbn, partner_id, loc, order_type, receiver_email, receiver_message, gift) VALUES ( ";
	$sql.= "'".$ordercode."', '".$oldtempkey."', '".$_ShopInfo->getMemid()."', ";
	$sql.= "'".$last_price."', '".$deli_price."', '".$dc_price."', '".$usereserve."', '".$pmethod."', ";
	$sql.= "'".$pay_data."', ";
	if($last_price==0) {
		$sql.= "'".date("YmdHis")."', ";
		if(preg_match("/^(O|Q)$/", $paymethod)) $sql.= "'0000', ";	//������¸�,,,
	}
	$sql.= "'".$sender_name."', '".$sender_email."', ";
	$sql.= "'".$sender_tel."', '".$receiver_name."', '".$receiver_tel1."', '".$receiver_tel2."', ";
	$sql.= "'".$receiver_addr."', '".$order_msg."', '".$ip."', '', '".$_ShopInfo->getRefurl()."', '".$loc."', '".$ordertype."', '".$receiver_email."', '".$receiver_message."','3')";
	mysql_query($sql,get_db_conn());
	backup_save_sql($sql);
	if (mysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",getenv("HTTP_HOST")."<br>$sql - ".mysql_error(),"Content-Type: text/plain\r\n");
	}
}

// ���� �������� �ٽ� �־� �ְų� ���ش�.
if(strlen($_ShopInfo->getMemid())>0 && $reserve_type=="Y" && $_data->reserve_maxuse>=0) {
	$sql = "UPDATE tblmember SET reserve=".abs($remain_reserve)." ";
	$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
	mysql_query($sql,get_db_conn());
}
$rtnpgtype="&rtnpgtype=3";
if($paymethod!="B") {
	########### �����ý��� ���� ���� ##########
	include($Dir.FrontDir."paylist.php");
	exit;
	########### �����ý��� ���� ��   ##########
}


########### ���� ������ ###########
include($Dir.FrontDir."payresult3.php");
########### ���� ������ �� ########
?>