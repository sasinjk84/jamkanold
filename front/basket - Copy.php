<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

/// POST INPUT
$mode=$_POST["mode"];
$code=$_POST["code"];
$ordertype=$_REQUEST["ordertype"];	//�ٷα��� ���� (�ٷα��Ž� => ordernow)
$opts=$_POST["opts"];	//�ɼǱ׷� ���õ� �׸� (��:1,1,2,)
$option1=$_POST["option1"];	//�ɼ�1
$option2=$_POST["option2"];	//�ɼ�2
$quantity=(int)$_REQUEST["quantity"];	//���ż���
if($quantity==0) $quantity=1;
$productcode=$_REQUEST["productcode"];

$orgquantity=$_POST["orgquantity"];
$orgoption1=$_POST["orgoption1"];
$orgoption2=$_POST["orgoption2"];

$assemble_type=$_POST["assemble_type"];
$assemble_list=@str_replace("|","",$_POST["assemble_list"]);
$assembleuse=$_POST["assembleuse"];
$assemble_idx=(int)$_POST["assemble_idx"];

$package_idx=(int)$_POST["package_idx"];

$sell_memid = $_POST["sell_memid"];

// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
$basket = basketTable($ordertype);

if( $ordertype != "" AND $ordertype != "pester" ){
	$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
	//mysql_query($sql,get_db_conn());
}


if($assemble_idx==0) {
	if($assembleuse=="Y") {
		$assemble_idx="99999";
	}
} else {
	$assembleuse="Y";
}


//��ٱ��� ����Ű Ȯ��
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
}

//sns ȫ�� ����üũ
if(strlen($_ShopInfo->getMemid()) > 0){
	$sql ="UPDATE ".$basket." SET sell_memid ='' WHERE tempkey='".$_ShopInfo->getTempkey()."' AND sell_memid='".$_ShopInfo->getMemid()."'";
	mysql_query($sql,get_db_conn());
}


if($_POST['act'] == 'del' ||$_POST['act'] == 'upd' ){
	$keys = array();
	if(!_empty($_POST['sbasketidx'])){
		if(preg_match('/^([0-9]+)(_[0-9]+)*$/',$_POST['sbasketidx'],$mat)){			
			$keys[] = $mat;
		}
	}else if(_array($basket_select_item)){
		foreach($basket_select_item as $item){
			if(preg_match('/^([0-9]+)(_[0-9]+)*$/',$item,$mat)) $keys[] = $mat;
		}
	}
	
	if(_array($keys)){
		if($_POST['act'] == 'del'){
			foreach($keys as $mat){
				$delmain = true;
				if(!_empty($mat[2])){
					$delmain = false;
					$sql = "delete from rent_basket_temp where rbtidx='".substr($mat[2],1)."' and basketidx='".$mat[1]."' limit 1";
					if(false === mysql_query($sql,get_db_conn())){
						_alert('���� ����1','-1');
						exit;
					}else{
						$sql = "select count(*) from rent_basket_temp where basketidx='".$mat[1]."'";
						if(false !== $res = mysql_query($sql,get_db_conn())) $delmain = (mysql_result($res,0,0) < 1);
					}
				}
				if($delmain){
					$sql = "delete from tblbasket where basketidx='".$mat[1]."' limit 1";
					if(false === mysql_query($sql,get_db_conn())) _alert('���� ����2','-1');
				}	
			}
		}else if($_POST['act'] == 'upd'){
			if(count($keys) >1) _alert('���� ������Ʈ ��ó��','-1');			
		}
		_alert('','/front/basket.php?ordertype='.$ordertype);
		exit;
	}else{
		_alert('����� ���� ���� �ʾҽ��ϴ�.','-1');
		exit;
	}	
}else if($_POST['act'] == 'upd'){
	if(!_empty($_POST['sbasketidx'])){
		if(preg_match('/^([0-9]+)(_[0-9]+)*$/',$_POST['sbasketidx'],$mat)){
			$delmain = true;
			if(!_empty($mat[2])){
				$delmain = false;
				$sql = "delete from rent_basket_temp where rbtidx='".substr($mat[2],1)."' and basketidx='".$mat[1]."' limit 1";
				if(false === mysql_query($sql,get_db_conn())){
					_alert('���� ����1','-1');
					exit;
				}else{
					$sql = "select count(*) from rent_basket_temp where basketidx='".$mat[1]."'";
					if(false !== $res = mysql_query($sql,get_db_conn())) $delmain = (mysql_result($res,0,0) < 1);
				}
			}
			if($delmain){
				$sql = "delete from tblbasket where basketidx='".$mat[1]."' limit 1";
				if(false === mysql_query($sql,get_db_conn())) _alert('���� ����2','-1');
			}			
		}	
	}else{
		
	}
}


//���� ������ ���� ���
if($_REQUEST['mode']=="coupon" && strlen($_REQUEST['coupon_code'])==8){
	$onload = '';
	if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
		echo "<html></head><body onload=\"alert('�α��� �� ���� �ٿ�ε尡 �����մϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	}else{
		$sql = "SELECT * FROM tblcouponinfo where coupon_code = '".$_REQUEST['coupon_code']."'";


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
				$sql.= "coupon_code	= '".$_REQUEST['coupon_code']."', ";
				$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
				$sql.= "date_start	= '".$date_start."', ";
				$sql.= "date_end	= '".$date_end."', ";
				$sql.= "date		= '".$date."' ";

				mysql_query($sql,get_db_conn());
				if(!mysql_errno()) {
					$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 ";
					$sql.= "WHERE coupon_code = '".$_REQUEST['coupon_code']."'";
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
						$sql.= "WHERE coupon_code='".$_REQUEST['coupon_code']."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn()) or die(mysql_error());
						$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";

					} else {
						$onload="<script>alert(\"�̹� ������ �߱޹����̽��ϴ�.\\n\\n�ش� ������ ��߱��� �Ұ����մϴ�.\");</script>";
					}
				}
			}
		}
		mysql_free_result($result);

	}

	if(!_empty($onload)){
		echo $onload;
	}
	?>
	<script language="javascript" type="text/javascript">
		document.location.replace('/front/basket.php');
	</script>
	<?
	exit;

}





if($mode=="clear") {	//��ٱ��� ����
	$rsql = "delete r from rent_basket_temp r inner join ".$basket." b on b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."'"; // ��Ʈ ���� �ӽ� ���̺� ����
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
	mysql_query($sql,get_db_conn());
} else if($mode == 'seldel'){
	$tmpidxs = explode(',',$_POST['basketidxs']);
	$basketidxs = array();
	foreach($tmpidxs as $val){
		if(!empty($val) && !preg_match('/[^0-9]/',$val)) array_push($basketidxs,$val);
	}
	if(count($basketidxs)){
		$rsql = "delete r from rent_basket_temp r inner join ".$basket." b b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."' AND b.basketidx in ('".implode("','",$basketidxs)."')"; // ��Ʈ ���� �ӽ� ���̺� ����
		mysql_query($sql,get_db_conn());
		
		$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND basketidx in ('".implode("','",$basketidxs)."') ";
		mysql_query($sql,get_db_conn());
	}else{
		_alert('��ٱ��Ͽ��� ������ ����� ���޵��� �ʾҽ��ϴ�.','-1');
	}
}else if(strlen($productcode)==18) {
	if(strlen($code)==0) {
		$code=substr($productcode,0,12);
	}
	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);
	if(strlen($codeA)!=3) $codeA="000";
	if(strlen($codeB)!=3) $codeB="000";
	if(strlen($codeC)!=3) $codeC="000";
	if(strlen($codeD)!=3) $codeD="000";

	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if($row->group_code=="NO") {	//���� �з�
			echo "<html></head><body onload=\"alert('�ǸŰ� ����� ��ǰ�Դϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//ȸ���� ���ٰ���
			echo "<html></head><body onload=\"alert('�α��� �ϼž� ��ٱ��Ͽ� ������ �� �ֽ��ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//�׷�ȸ���� ����
			echo "<html></head><body onload=\"alert('�ش� �з��� ���� ������ �����ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		}

		//Wishlist ���
		if($mode=="wishlist") {
			if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
				echo "<html></head><body onload=\"alert('�α����� �ϼž� �� ���񽺸� �̿��Ͻ� �� �ֽ��ϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
			}
			$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
			$result2=mysql_query($sql,get_db_conn());
			$row2=mysql_fetch_object($result2);
			$totcnt=$row2->totcnt;
			mysql_free_result($result2);
			$maxcnt=20;
			if($totcnt>=$maxcnt) {
				$sql = "SELECT b.productcode FROM tblwishlist a, tblproduct b ";
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
					$sql = "UPDATE tblwishlist SET date='".date("YmdHis")."' ";
					$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
					$sql.= "AND productcode='".$productcode."' ";
					$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
					mysql_query($sql,get_db_conn());

					echo "<html></head><body onload=\"alert('WishList�� �̹� ��ϵ� ��ǰ�Դϴ�.');history.go(-1);\"></body></html>";exit;
				} else {
					$sql = "INSERT tblwishlist SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "productcode	= '".$productcode."' ";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('WishList�� �ش� ��ǰ�� ����Ͽ����ϴ�.');history.go(-1);\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('WishList���� ".$maxcnt."�� ������ ����� �����մϴ�.\\n\\nWishList���� �ٸ� ��ǰ�� �����Ͻ� �� ����Ͻñ� �ٶ��ϴ�.');history.go(-1);\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� �з��� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
	}
	mysql_free_result($result);
}


$errmsg="";

if($mode!="clear" && $mode!="seldel" && $mode!="wishlist" && strlen($productcode)==18) {	
	//�ش��ǰ����, ��ٱ��ϴ��, �ٷα���, ���� ������Ʈ, �������Žÿ�...
	if($mode!="del" && strlen($quantity)>0 && $quantity<=0 && strlen($productcode)==18) {
		echo "<html></head><body onload=\"alert('���ż����� �߸��Ǿ����ϴ�.');history.go(-1);\"></body></html>";
		exit;
	}

	//��ٱ��� ��� �Ǵ� ����/�ɼ� ������Ʈ
	if($mode!="del" && strlen($quantity)>0 && strlen($productcode)==18) {
		$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check,assembleuse,package_num FROM tblproduct ";
		$sql.= "WHERE productcode='".$productcode."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->display!="Y") {
				$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
			}

			$proassembleuse = $row->assembleuse;

			if($mode=="upd") {
				$sql2 = "SELECT SUM(quantity) as quantity FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
				$sql2.= "AND productcode='".$productcode."' ";
				$sql2.= "GROUP BY productcode ";
				$result2 = mysql_query($sql2,get_db_conn());
				if($row2 = mysql_fetch_object($result2)) {
					$rowcnt=$row2->quantity;
				} else {
					$rowcnt=0;
				}
				mysql_free_result($result2);

				$charge_quantity = -($orgquantity-$quantity);
				$rowcnt=$rowcnt+$charge_quantity;
			} else {
				$rowcnt=$quantity;
				$charge_quantity=$quantity;
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

				if(count($assemble_list_exp)>0) {
					for($i=0; $i<count($assemble_list_exp); $i++) {
						if(strlen($assemble_list_exp[$i])>0) {
							$assemble_proquantity[$assemble_list_exp[$i]]+=$charge_quantity;
						}
					}
					$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
					$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
					$assemprosql.= "AND display = 'Y' ";
					$assemproresult=mysql_query($assemprosql,get_db_conn());
					while($assemprorow=@mysql_fetch_object($assemproresult)) {
						if(strlen($assemprorow->quantity)>0) {
							if($assemble_proquantity[$assemprorow->productcode] > $assemprorow->quantity) {
								if($assemprorow->quantity>0) {
									$errmsg.="�ش� ��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$assemprorow->quantity." �� �Դϴ�.")."\\n";
								} else {
									$errmsg.="�ش� ��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
								}
							}
						}
					}
					@mysql_free_result($assemproresult);
				} else if(strlen($package_productcode_tmp)>0) {
					$assemble_proquantity[$productcode]+=$charge_quantity;
					$package_productcode_tmpexp = explode("",$package_productcode_tmp);
					$package_quantity_tmpexp = explode("",$package_quantity_tmp);
					$package_productname_tmpexp = explode("",$package_productname_tmp);
					for($i=0; $i<count($package_productcode_tmpexp); $i++) {
						if(strlen($package_productcode_tmpexp[$i])>0) {
							$assemble_proquantity[$package_productcode_tmpexp[$i]]+=$charge_quantity;

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
				} else {
					$assemble_proquantity[$productcode]+=$charge_quantity;
					if(strlen($row->quantity)>0) {
						if ($assemble_proquantity[$productcode] > $row->quantity) {
							if ($row->quantity>0)
								$errmsg.="�ش� ��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
							else
								$errmsg.= "�ش� ��ǰ�� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
						}
					}
				}

				if(strlen($row->option_quantity)>0) {
					$optioncnt = explode(",",substr($row->option_quantity,1));
					if($option2==0) $tmoption2=1;
					else $tmoption2=$option2;
					$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
					if($optionvalue<=0 && $optionvalue!="") {
						$errmsg.="�ش� ��ǰ�� ���õ� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
					} else if($optionvalue<$quantity && $optionvalue!="") {
						$errmsg.="�ش� ��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
					} else {
						if($mode=="upd") {
							if (empty($option1))  $option1=0;
							if (empty($option2))  $option2=0;
							if (empty($opts))  $opts="0";
							if (empty($assemble_idx))  $assemble_idx=0;

							$samesql = "SELECT * FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
							$samesql.= "AND productcode='".$productcode."' ";
							$samesql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
							$samesql.= "AND assemble_idx = '".$assemble_idx."' ";
							$sameresult = mysql_query($samesql,get_db_conn());
							$samerow=mysql_fetch_object($sameresult);
							mysql_free_result($sameresult);
							if($samerow && ($option1!=$orgoption1 || $option2!=$orgoption2)) {
								if($optionvalue<($samerow->quantity + $quantity) && $optionvalue!="") {
									$errmsg.="�ش� ��ǰ�� ���õ� �ɼǰ� �ߺ���ǰ�� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
								}
							}
						}
					}
				}
			}
		} else {
			$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.\\n";
		}
		mysql_free_result($result);

		if(strlen($errmsg)>0) {
			echo "<html></head><body onload=\"alert('".$errmsg."');location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
			exit;
		}
	}

	// �̹� ��ٱ��Ͽ� ��� ��ǰ���� �˻��Ͽ� ������ ī��Ʈ�� ����.
	if (empty($option1))  $option1=0;
	if (empty($option2))  $option2=0;
	if (empty($opts))  $opts="0";
	if (empty($assemble_idx))  $assemble_idx=0;

	if($proassembleuse=="Y") {
		$assemaxsql = "SELECT MAX(assemble_idx) AS assemble_idx_max FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
		$assemaxsql.= "AND productcode='".$productcode."' ";
		$assemaxsql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
		$assemaxsql.= "AND assemble_idx > 0 ";
		$assemaxresult = mysql_query($assemaxsql,get_db_conn());
		$assemaxrow=@mysql_fetch_object($assemaxresult);
		@mysql_free_result($assemaxresult);
		$assemble_idx_max = $assemaxrow->assemble_idx_max+1;
	} else {
		$assemble_idx_max = 0;
	}

	$sql = "SELECT * FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND productcode='".$productcode."' ";
	$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
	$sql.= "AND assemble_idx = '".$assemble_idx."' ";
	$sql.= "AND package_idx = '".$package_idx."' ";

	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row) $productexists = true;
	mysql_free_result($result);

	if ($mode=="del") {
		$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' ";
		$sql.= "AND opt1_idx='".$orgoption1."' AND opt2_idx='".$orgoption2."' AND optidxs='".$opts."' ";
		$sql.= "AND assemble_idx = '".$assemble_idx."' ";
		$sql.= "AND package_idx = '".$package_idx."' ";
		mysql_query($sql,get_db_conn());
	} else if ($mode=="upd") {
		if (($option1==$orgoption1 && $option2==$orgoption2) || !($row)) {
			$sql = "UPDATE ".$basket." SET ";
			$sql.= "quantity		= '".$quantity."', ";
			$sql.= "opt1_idx		= '".$option1."', ";
			$sql.= "opt2_idx		= '".$option2."' ";
			$sql.= "WHERE tempkey	='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode	='".$productcode."' AND opt1_idx='".$orgoption1."' ";
			$sql.= "AND opt2_idx	='".$orgoption2."' AND optidxs='".$opts."' ";
			$sql.= "AND assemble_idx = '".$assemble_idx."' ";
			$sql.= "AND package_idx = '".$package_idx."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$c = $row->quantity + $quantity;
			$sql = "UPDATE ".$basket." SET quantity='".$c."', opt1_idx='".$option1."' ";
			$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='".$productcode."' AND opt1_idx='".$option1."' ";
			$sql.= "AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
			$sql.= "AND assemble_idx = '".$assemble_idx."' ";
			$sql.= "AND package_idx = '".$package_idx."' ";
			mysql_query($sql,get_db_conn());

			$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' ";
			$sql.= "AND opt1_idx='".$orgoption1."' AND opt2_idx='".$orgoption2."' AND optidxs='".$opts."' ";
			$sql.= "AND assemble_idx = '".$assemble_idx."' ";
			$sql.= "AND package_idx = '".$package_idx."' ";
			mysql_query($sql,get_db_conn());
		}
	} else if($row && !isRentProduct($productcode)){
		$onload="<script>alert('�̹� ��ٱ��Ͽ� ��ǰ�� ����ֽ��ϴ�. ������ �����Ͻ÷��� �����Է��� �����ϼ���.');</script>";
	} else {

		if (strlen($productcode)==18) {
			// �뿩 �Ⱓ ���� üũ
			$pridx = productcodeToPridx($productcode);
			if('pass' != $msg = rentOrderChecker($pridx,trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']),trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']),$_REQUEST['rentOptionList'])) {
				//_alert($msg."\\n������Ȳ�� Ȯ���ϼ���!",$Dir.FrontDir."productdetail.php?productcode=".$productcode);

				echo $msg;
				exit;
			}
			
			$vdate = date("YmdHis");
			$sql = "SELECT COUNT(*) as cnt, ordertype FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$result = mysql_query($sql,get_db_conn());
			$row = mysql_fetch_object($result);
			mysql_free_result($result);
			if($row->cnt>=200) {
				echo "<script>alert('��ٱ��Ͽ��� �� 200�������� ������ �ֽ��ϴ�.');</script>";
			} else {	
				if(!isRentProduct($pridx) || !$productexists){
					$sql = "INSERT ".$basket." SET ";
					$sql.= "tempkey			= '".$_ShopInfo->getTempkey()."', ";
					$sql.= "productcode		= '".$productcode."', ";
					$sql.= "opt1_idx		= '".$option1."', ";
					$sql.= "opt2_idx		= '".$option2."', ";
					$sql.= "optidxs			= '".$opts."', ";
					$sql.= "quantity		= '".$quantity."', ";
					$sql.= "package_idx	= '".$package_idx."', ";
					$sql.= "assemble_idx	= '".$assemble_idx_max."', ";
					$sql.= "assemble_list	= '".$assemble_list."', ";
					$sql.= "date			= '".$vdate."', ";
					$sql.= "sell_memid		= '".$sell_memid."', ";
					$sql.= "ordertype		= '".$ordertype."' ";
					echo $sql;
					mysql_query($sql,get_db_conn());
				}
		
				if(isRentProduct($pridx)){				
					// �뿩 ��ٱ��� ��ǰ �뿩�Ⱓ ����
					//if(strlen($_REQUEST['p_bookingStartDate']) == 8 AND strlen($_REQUEST['p_bookingEndDate']) == 8 AND strlen($_REQUEST['rentOptionList']) > 0 ){
					$rentreturn = rentProduct::insertBasket($basket,$_ShopInfo->getTempkey(),$_REQUEST['productcode'],$_REQUEST['rentOptionList'],$_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime'],$_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']);								
					
					if($rentreturn['err'] != 'ok'){
						$sql = "select count(r.*) from tblbasket as b inner join rent_basket_temp r on r.basketidx=b.basketidx where basketidx='".$mat[1]."' where b.tempkey= '".$_ShopInfo->getTempkey()."' and b.productcode='".$productcode."'";
						if(false !== $res = mysql_query($sql,get_db_conn())){
							if(mysql_result($res) < 1) mysql_query("delete from ".$basket." where tempkey= '".$_ShopInfo->getTempkey()."' and productcode='".$productcode."'",get_db_conn());							
							_alert($rentreturn['err'],'-1');
						}

					}
				}		
				if( !_empty($ordertype) && $ordertype != "pester" ){
					echo "<script type=\"text/javascript\">location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode( $Dir.FrontDir."order.php?ordertype=".$ordertype )."';</script>";
					exit;
				}
				_alert('','/front/basket.php?ordertype='.$ordertype);
				exit;
			}
		}
	}
}

$mycoupon_codes = getMyCouponList('',true);


//��ٱ��� ���̺� ����
$tblbasket = $basket;





// ��ٱ��� ������ (Array) ==================================================
$basketItems = getBasketByArray($basket);
?>
<DIV style="width:100%; height:300px; overflow:scroll">
<? _pr($basketItems); ?>
</DIV>
<?

/*
echo "<div style=\" height:500px; overflow:scroll;  border:2px solid #ff0000 ;  text-align:left;\">";
_pr($basketItems);
echo "</div>";
*/



/*
ȸ�� ��� ���� �޼��� ============
	RW : �ݾ� �߰� ����
	RP  : % �߰� ����
	SW : �ݾ� �߰� ����
	SP  : % �߰� ����
*/
$groupMemberSale = "";
if( $basketItems['groupMemberSale'] ) {
	$groupMemberSale .= "
		<font style=\"letter-spacing:0px;\"><b>ȸ���������</b> : ".$basketItems['groupMemberSale']['name']."</font>��(".$basketItems['groupMemberSale']['group'].")��
		<font color=\"#ee0a02\" style=\"letter-spacing:0px;\">".number_format($basketItems['groupMemberSale']['useMoney'])."��</font> �̻�
		<font  color=\"#ee0a02\">".$basketItems['groupMemberSale']['payType']."</font> ������<br />
	";
	if($basketItems['groupMemberSale']['groupCode']=="RW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>".number_format($basketItems['groupMemberSale']['addMoney'])."</b>��</font>�� �������� �߰��� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="RP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>�� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ� ".number_format($basketItems['groupMemberSale']['addMoney'])."��</b></font>�� �߰��� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>�� �߰��� ������ �帳�ϴ�.";
	}
}

?>

<HTML>
<HEAD>
	<TITLE><?=$_data->shoptitle?> - ��ٱ���</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

	<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
	<META name="keywords" content="<?=$_data->shopkeyword?>">
	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
	<script type="text/javascript" src="<?=$Dir?>lib/DropDown.js.php"></script>
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script language="javascript" type="text/javascript">
		var $j =jQuery.noConflict();
	</script>
	<? include($Dir."lib/style.php")?>
	<script language="javascript" type="text/javascript">
		<!--
		function checkSelect(){
			var f = document.basketForm;
			var selitems = $j(f).find('input[name=basket_select_item\[\]]:checked');
			return selitems.length;
		}
		
		function CheckForm(mode,idx) {
			var f = document.basketForm;
			if(mode=="del") {
				if(idx=='sel'){
					/*
					var selitems = $j('input[name=basket_select_item]:checked');
					var itemval = '';

					if(selitems.length <1){
						alert('���õ� �׸��� �����ϴ�.');
					}else if(confirm("���õ� �׸��� ��ٱ��Ͽ��� �����Ͻðڽ��ϱ�?")){
						$j.each(selitems,function(idx,el){
							itemval+= $j(el).val()+',';
						});
						$j('#selItemForm').find('input[name=mode]').val('seldel');
						$j('#selItemForm').find('input[name=basketidxs]').val(itemval);
						$j('#selItemForm').submit();
						//alert(itemval);
					}*/
					//basketForm					
				
				}else if(confirm("�ش� ��ǰ�� ��ٱ��Ͽ��� �����Ͻðڽ��ϱ�?")) {
					f.sbasketidx.value = idx;										
				}
			} else if(mode=="upd"){				
				if(document["form_"+idx].quantity.value.length==0 || document["form_"+idx].quantity.value==0) {
					alert("������ �Է��ϼ���.");
					document["form_"+idx].quantity.focus();
					return;
				}
				if(!IsNumeric(document["form_"+idx].quantity.value)) {
					alert("������ ���ڸ� �Է��ϼ���.");
					document["form_"+idx].quantity.focus();
					return;
				}
				
				document["form_"+idx].submit();
			}
			f.act.value=mode;
			f.submit();
		}

		function change_quantity(gbn,idx) {
			tmp=document["form_"+idx].quantity.value;
			if(gbn=="up") {
				tmp++;
			} else if(gbn=="dn") {
				if(tmp>1) tmp--;
			}
			document["form_"+idx].quantity.value=tmp;
		}
		function go_wishlist(idx) {
			document.wishform.productcode.value=document["form_"+idx].productcode.value;
			document.wishform.opts.value=document["form_"+idx].opts.value;
			document.wishform.option1.value=document["form_"+idx].orgoption1.value;
			document.wishform.option2.value=document["form_"+idx].orgoption2.value;
			window.open("about:blank","confirmwishlist","width=500,height=300,scrollbars=no");
			document.wishform.submit();
		}
		function basket_clear() {
			if(confirm("��ٱ��ϸ� ���ðڽ��ϱ�?")) {
				document.delform.mode.value="clear";
				document.delform.submit();
			}
		}
		function check_login() {
			if(confirm("�α����� �ʿ��� �����Դϴ�. �α����� �Ͻðڽ��ϱ�?")) {
				document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
			}
		}

		function setPackageShow(packageid) {
			if(packageid.length>0 && document.getElementById(packageid)) {
				if(document.getElementById(packageid).style.display=="none") {
					document.getElementById(packageid).style.display="";
				} else {
					document.getElementById(packageid).style.display="none";
				}
			}
		}

		<?if($_data->oneshot_ok=="Y" || $_data->design_basket=="U") {?>
		var imagepath="<?=$Dir.DataDir?>shopimages/product/";
		var default_primage="oneshot_primage<?=$_data->design_basket?>.gif";
		var prall=new Array();
		function pralllist() {
			var argv = pralllist.arguments;
			var argc = pralllist.arguments.length;

			//Property ����
			this.classname		= "pralllist"								//classname
			this.debug			= false;									//����뿩��.
			this.productcode	= new String((argc > 0) ? argv[0] : "");
			this.tinyimage		= new String((argc > 1) ? argv[1] : "");
			this.option1		= ToInt((argc > 2) ? argv[2] : 1);
			this.option2		= ToInt((argc > 3) ? argv[3] : 1);
			this.quantity		= ToInt((argc > 4) ? argv[4] : 1);
			this.miniq			= ToInt((argc > 5) ? argv[5] : 1);
			this.assembleuse	= new String((argc > 6) ? argv[6] : "N");
			this.package_num	= new String((argc > 7) ? argv[7] : "");
		}

		function CheckCode() {
			form=document.form1;
			if(form.codeA.value.length==3 && form.codeB.value.length==3 && form.codeC.value.length==3 && form.codeD.value.length==3) {
				form.submit();
			} else {
				form.tmpprcode.options.length=1;
				var d = new Option("��ǰ ����");
				form.tmpprcode.options[0] = d;
				form.tmpprcode.options[0].value = "";

				document.all["oneshot_primage"].src="<?=$Dir?>images/common/basket/"+default_primage;
				form.productcode.value="";
				form.quantity.value="";
				form.option1.value="";
				form.option2.value="";
			}
		}

		function CheckProduct() {
			form=document.form1;
			if(form.tmpprcode.value.length==0) {
				document.all["oneshot_primage"].src="<?=$Dir?>images/common/basket/"+default_primage;
				form.productcode.value="";
				form.quantity.value="";
				form.option1.value="";
				form.option2.value="";
				form.assembleuse.value="";
				form.package_num.value="";
			} else {
				productcode=prall[form.tmpprcode.value].productcode;
				tinyimage=prall[form.tmpprcode.value].tinyimage;
				option1=prall[form.tmpprcode.value].option1;
				option2=prall[form.tmpprcode.value].option2;
				quantity=prall[form.tmpprcode.value].miniq;
				assembleuse=prall[form.tmpprcode.value].assembleuse;
				package_num=prall[form.tmpprcode.value].package_num;
				if(tinyimage.length>0) {
					document.all["oneshot_primage"].src=imagepath+tinyimage;
				} else {
					document.all["oneshot_primage"].src="<?=$Dir?>images/common/basket/"+default_primage;
				}
				form.productcode.value=productcode;
				form.quantity.value=quantity;
				form.option1.value=option1;
				form.option2.value=option2;
				form.assembleuse.value=assembleuse;
				form.package_num.value=package_num;
			}
		}

		function OneshotBasketIn() {
			if(document.form1.productcode.value.length!=18) {
				alert("��ǰ�� �����ϼ���.");
				document.form1.tmpprcode.focus();
				return;
			}
			if(document.form1.assembleuse.value=="Y") {
				if(confirm("�ش� ��ǰ�� ������ǰ�� �����ؾ߸� ���Ű� ������ ��ǰ�Դϴ�.\n\n         ��ǰ ������������ ������ �ϰڽ��ϱ�?")) {
					location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+document.form1.productcode.value;
				}
			} else if(document.form1.package_num.value.length>0) {
				if(confirm("�ش� ��ǰ�� ��Ű�� ���� ��ǰ���ν� ��ǰ������������ ��Ű�� ������ Ȯ�� �� �ּ���.\n\n                              ��ǰ���������� �̵� �ϰڽ��ϱ�?")) {
					location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+document.form1.productcode.value;
				}
			} else {
				document.form1.submit();
			}
		}
		<?}?>




		// ��ٱ��� ��Ż ��ǰ �ɼ� ����
		function rentOptionCHG ( pridx, ordertype ) {
			window.open("/front/rentBasketOptionCHG_pop.php?pridx="+pridx+"&ordertype="+ordertype,"rentBasketOptionCHG_pop","width=500,height=800");
		}

		//-->
	</SCRIPT>
	<link type="text/css" rel="stylesheet" href="/css/newUI.css" />
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<?
include ($Dir.MainDir.$_data->menu_type.".php");
?>
<form name="selItemForm" id="selItemForm" method="post" action="<?=$Dir.FrontDir."basket.php"?>">
	<input type=hidden name="mode" value="">
	<input type=hidden name="basketidxs" value=""/>
	<input type=hidden name="ordertype" value="<?=$ordertype?>"/>
</form>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?
	$leftmenu="Y";
	if($_data->design_basket=="U") {
		$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='basket'";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
			$body=str_replace("[DIR]",$Dir,$body);
			$leftmenu=$row->leftmenu;
			$newdesign="Y";
		}
		mysql_free_result($result);
	}

	if ($leftmenu!="N") {
		echo "<tr>\n";
		if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/basket_title.gif")) {
			echo "<td><img src=\"".$Dir.DataDir."design/basket_title.gif\" border=\"0\" alt=\"��ٱ���\"></td>\n";
		} else {
			echo "<td>\n";
			/*
			echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
			echo "<TR>\n";
			echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/basket_title_head.gif ALT=></TD>\n";
			echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/basket_title_bg.gif></TD>\n";
			echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/basket_title_tail.gif ALT=></TD>\n";
			echo "</TR>\n";
			echo "</TABLE>\n";
			*/
			echo "</td>\n";
		}
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "	<td align=\"center\">\n";
	//echo $Dir.TempletDir."basket/basket".$_data->design_basket.".php";
	include ($Dir.TempletDir."basket/basket".$_data->design_basket.".php");
	echo "	</td>\n";
	echo "</tr>\n";

	if($ordertype=="ordernow") {	//�ٷα���
		if($sumprice>=$_data->bank_miniprice) {
			//echo "<script>location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode($Dir.FrontDir."order.php")."';</script>";
			exit;
		} else {
			//$onload="<script>alert('".number_format($_data->bank_miniprice)."�� �̻� ���Ű� �����մϴ�.(code:071)');</script>";
		}
	}

	?>
	<script type="text/javascript">
		var arPresent = new Array(<?for($i=0;$i<sizeof($arPresent);$i++) { if ($i!=0) { echo ",";} echo "'".$arPresent[$i]."'"; } ?>);
		var arPester = new Array(<?for($i=0;$i<sizeof($arPester);$i++) { if ($i!=0) { echo ",";} echo "'".$arPester[$i]."'"; } ?>);
		var pname ="";
		function chkPresent(){
			pname ="";
			for(i=0;i<arPresent.length;i++)
			{
				if(arPresent[i] == "N"){
					obj = document.forms["form_"+i];
					pname = pname + obj.productname.value +"\n";
				}
			}
			if(pname){
				alert(pname + " \n�����ϱ� �Ұ��� ��ǰ�Դϴ�.\n�ش� ��ǰ�� �����Ͻð� �ٽ��ѹ� �õ����ּ���");
			}else{
				location.href ='<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php?ordertype=present")?>';
			}
		}
		function chkPester(){
			pname ="";
			for(i=0;i<arPester.length;i++)
			{
				if(arPester[i] == "N"){
					obj = document.forms["form_"+i];
					pname = pname + obj.productname.value +"\n";
				}
			}
			if(pname){
				alert(pname + " \n������ �Ұ��� ��ǰ�Դϴ�.\n�ش� ��ǰ�� �����Ͻð� �ٽ��ѹ� �õ����ּ���");
			}else{
				location.href ='<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php?ordertype=pester")?>';
			}
		}


		<?if($_data->coupon_ok=="Y") {?>
		function issue_coupon(coupon_code,productcode){
			document.couponissueform.mode.value="coupon";
			document.couponissueform.coupon_code.value=coupon_code;
			document.couponissueform.productcode.value=productcode;
			document.couponissueform.submit();
		}
		<?}?>

	</script>

	<form name=couponissueform method=get action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=mode value="">
		<input type=hidden name=coupon_code value="">
		<input type=hidden name=productcode value="">
	</form>

	<form name=wishform method=post action="<?=$Dir.FrontDir?>confirm_wishlist.php" target="confirmwishlist">
		<input type=hidden name=productcode>
		<input type=hidden name=opts>
		<input type=hidden name=option1>
		<input type=hidden name=option2>
	</form>
	<form name=delform method=post action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=mode>
		<input type=hidden name=code value="<?=$code?>">
		<input type=hidden name=productcode>
		<input type=hidden name=ordertype value="<?=$ordertype?>">
	</form>
</table>

<? include ($Dir."lib/bottom.php") ?>

<?=$onload?>

</BODY>
</HTML>