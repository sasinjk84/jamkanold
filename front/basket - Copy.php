<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

/// POST INPUT
$mode=$_POST["mode"];
$code=$_POST["code"];
$ordertype=$_REQUEST["ordertype"];	//바로구매 구분 (바로구매시 => ordernow)
$opts=$_POST["opts"];	//옵션그룹 선택된 항목 (예:1,1,2,)
$option1=$_POST["option1"];	//옵션1
$option2=$_POST["option2"];	//옵션2
$quantity=(int)$_REQUEST["quantity"];	//구매수량
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

// 주문타입별 장바구니 테이블
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


//장바구니 인증키 확인
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
}

//sns 홍보 본인체크
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
						_alert('삭제 실패1','-1');
						exit;
					}else{
						$sql = "select count(*) from rent_basket_temp where basketidx='".$mat[1]."'";
						if(false !== $res = mysql_query($sql,get_db_conn())) $delmain = (mysql_result($res,0,0) < 1);
					}
				}
				if($delmain){
					$sql = "delete from tblbasket where basketidx='".$mat[1]."' limit 1";
					if(false === mysql_query($sql,get_db_conn())) _alert('삭제 실패2','-1');
				}	
			}
		}else if($_POST['act'] == 'upd'){
			if(count($keys) >1) _alert('다중 업데이트 미처리','-1');			
		}
		_alert('','/front/basket.php?ordertype='.$ordertype);
		exit;
	}else{
		_alert('대상이 전달 되지 않았습니다.','-1');
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
					_alert('삭제 실패1','-1');
					exit;
				}else{
					$sql = "select count(*) from rent_basket_temp where basketidx='".$mat[1]."'";
					if(false !== $res = mysql_query($sql,get_db_conn())) $delmain = (mysql_result($res,0,0) < 1);
				}
			}
			if($delmain){
				$sql = "delete from tblbasket where basketidx='".$mat[1]."' limit 1";
				if(false === mysql_query($sql,get_db_conn())) _alert('삭제 실패2','-1');
			}			
		}	
	}else{
		
	}
}


//쿠폰 발행이 있을 경우
if($_REQUEST['mode']=="coupon" && strlen($_REQUEST['coupon_code'])==8){
	$onload = '';
	if(strlen($_ShopInfo->getMemid())==0) {	//비회원
		echo "<html></head><body onload=\"alert('로그인 후 쿠폰 다운로드가 가능합니다.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	}else{
		$sql = "SELECT * FROM tblcouponinfo where coupon_code = '".$_REQUEST['coupon_code']."'";


		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
				$onload="<script>alert(\"모든 쿠폰이 발급되었습니다.\");</script>";
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

					$onload="<script>alert(\"해당 쿠폰 발급이 완료되었습니다.\\n\\n상품 주문시 해당 쿠폰을 사용하실 수 있습니다.\");</script>";
				} else {

					if($row->repeat_id=="Y") {	//동일인 재발급이 가능하다면,,,,
						$sql = "UPDATE tblcouponissue SET ";
						if($row->date_start<=0) {
							$sql.= "date_start	= '".$date_start."', ";
							$sql.= "date_end	= '".$date_end."', ";
						}
						$sql.= "used		= 'N' ";
						$sql.= "WHERE coupon_code='".$_REQUEST['coupon_code']."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn()) or die(mysql_error());
						$onload="<script>alert(\"해당 쿠폰 발급이 완료되었습니다.\\n\\n상품 주문시 해당 쿠폰을 사용하실 수 있습니다.\");</script>";

					} else {
						$onload="<script>alert(\"이미 쿠폰을 발급받으셨습니다.\\n\\n해당 쿠폰은 재발급이 불가능합니다.\");</script>";
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





if($mode=="clear") {	//장바구니 비우기
	$rsql = "delete r from rent_basket_temp r inner join ".$basket." b on b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."'"; // 렌트 관련 임시 테이블 삭제
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
		$rsql = "delete r from rent_basket_temp r inner join ".$basket." b b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."' AND b.basketidx in ('".implode("','",$basketidxs)."')"; // 렌트 관련 임시 테이블 삭제
		mysql_query($sql,get_db_conn());
		
		$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND basketidx in ('".implode("','",$basketidxs)."') ";
		mysql_query($sql,get_db_conn());
	}else{
		_alert('장바구니에서 삭제할 대상이 전달되지 않았습니다.','-1');
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
		if($row->group_code=="NO") {	//숨김 분류
			echo "<html></head><body onload=\"alert('판매가 종료된 상품입니다.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//회원만 접근가능
			echo "<html></head><body onload=\"alert('로그인 하셔야 장바구니에 담으실 수 있습니다.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//그룹회원만 접근
			echo "<html></head><body onload=\"alert('해당 분류의 접근 권한이 없습니다.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		}

		//Wishlist 담기
		if($mode=="wishlist") {
			if(strlen($_ShopInfo->getMemid())==0) {	//비회원
				echo "<html></head><body onload=\"alert('로그인을 하셔야 본 서비스를 이용하실 수 있습니다.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
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

					echo "<html></head><body onload=\"alert('WishList에 이미 등록된 상품입니다.');history.go(-1);\"></body></html>";exit;
				} else {
					$sql = "INSERT tblwishlist SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "productcode	= '".$productcode."' ";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('WishList에 해당 상품을 등록하였습니다.');history.go(-1);\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('WishList에는 ".$maxcnt."개 까지만 등록이 가능합니다.\\n\\nWishList에서 다른 상품을 삭제하신 후 등록하시기 바랍니다.');history.go(-1);\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('해당 분류가 존재하지 않습니다.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
	}
	mysql_free_result($result);
}


$errmsg="";

if($mode!="clear" && $mode!="seldel" && $mode!="wishlist" && strlen($productcode)==18) {	
	//해당상품삭제, 장바구니담기, 바로구매, 수량 업데이트, 원샷구매시에...
	if($mode!="del" && strlen($quantity)>0 && $quantity<=0 && strlen($productcode)==18) {
		echo "<html></head><body onload=\"alert('구매수량이 잘못되었습니다.');history.go(-1);\"></body></html>";
		exit;
	}

	//장바구니 담기 또는 수량/옵션 업데이트
	if($mode!="del" && strlen($quantity)>0 && strlen($productcode)==18) {
		$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check,assembleuse,package_num FROM tblproduct ";
		$sql.= "WHERE productcode='".$productcode."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->display!="Y") {
				$errmsg="해당 상품은 판매가 되지 않는 상품입니다.\\n";
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
							$errmsg="해당 상품은 지정 등급 전용 상품입니다.\\n";
						}
						@mysql_free_result($resultgc);
					} else {
						$errmsg="해당 상품은 지정 등급 전용 상품입니다.\\n";
					}
				} else {
					$errmsg="해당 상품은 회원 전용 상품입니다.\\n";
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
					$errmsg="해당 상품은 판매가 되지 않습니다. 다른 상품을 주문해 주세요.\\n";
				}
			}
			if(strlen($errmsg)==0) {
				if ($miniq!=1 && $miniq>1 && $rowcnt<$miniq)
					$errmsg="해당 상품은 최소 ".$miniq."개 이상 주문하셔야 합니다.\\n";
				if ($maxq!="?" && $maxq>0 && $rowcnt>$maxq)
					$errmsg.="해당 상품은 최대 ".$maxq."개 이하로 주문하셔야 합니다.\\n";

				if(empty($option1) && strlen($row->option1)>0)  $option1=1;
				if(empty($option2) && strlen($row->option2)>0)  $option2=1;
				if(strlen($row->quantity)>0) {
					if ($rowcnt>$row->quantity) {
						if ($row->quantity>0)
							$errmsg.="해당 상품의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 ".$row->quantity." 개 입니다.")."\\n";
						else
							$errmsg.= "해당 상품이 다른 고객의 주문으로 품절되었습니다.\\n";
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
									$errmsg.="해당 상품의 구성상품 [".ereg_replace("'","",$assemprorow->productname)."] 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 ".$assemprorow->quantity." 개 입니다.")."\\n";
								} else {
									$errmsg.="해당 상품의 구성상품 [".ereg_replace("'","",$assemprorow->productname)."] 다른 고객의 주문으로 품절되었습니다.\\n";
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
										$errmsg.="해당 상품의 패키지 [".ereg_replace("'","",$package_productname_tmpexp[$i])."] 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 ".$package_quantity_tmpexp[$i]." 개 입니다.")."\\n";
									} else {
										$errmsg.="해당 상품의 패키지 [".ereg_replace("'","",$package_productname_tmpexp[$i])."] 다른 고객의 주문으로 품절되었습니다.\\n";
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
								$errmsg.="해당 상품의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 ".$row->quantity." 개 입니다.")."\\n";
							else
								$errmsg.= "해당 상품이 다른 고객의 주문으로 품절되었습니다.\\n";
						}
					}
				}

				if(strlen($row->option_quantity)>0) {
					$optioncnt = explode(",",substr($row->option_quantity,1));
					if($option2==0) $tmoption2=1;
					else $tmoption2=$option2;
					$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
					if($optionvalue<=0 && $optionvalue!="") {
						$errmsg.="해당 상품의 선택된 옵션은 다른 고객의 주문으로 품절되었습니다.\\n";
					} else if($optionvalue<$quantity && $optionvalue!="") {
						$errmsg.="해당 상품의 선택된 옵션의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"$optionvalue 개 입니다.")."\\n";
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
									$errmsg.="해당 상품의 선택된 옵션과 중복상품의 옵션의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"$optionvalue 개 입니다.")."\\n";
								}
							}
						}
					}
				}
			}
		} else {
			$errmsg="해당 상품이 존재하지 않습니다.\\n";
		}
		mysql_free_result($result);

		if(strlen($errmsg)>0) {
			echo "<html></head><body onload=\"alert('".$errmsg."');location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
			exit;
		}
	}

	// 이미 장바구니에 담긴 상품인지 검사하여 있으면 카운트만 증가.
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
		$onload="<script>alert('이미 장바구니에 상품이 담겨있습니다. 수량을 조절하시려면 수량입력후 수정하세요.');</script>";
	} else {

		if (strlen($productcode)==18) {
			// 대여 기간 가능 체크
			$pridx = productcodeToPridx($productcode);
			if('pass' != $msg = rentOrderChecker($pridx,trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']),trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']),$_REQUEST['rentOptionList'])) {
				//_alert($msg."\\n예약현황을 확인하세요!",$Dir.FrontDir."productdetail.php?productcode=".$productcode);

				echo $msg;
				exit;
			}
			
			$vdate = date("YmdHis");
			$sql = "SELECT COUNT(*) as cnt, ordertype FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$result = mysql_query($sql,get_db_conn());
			$row = mysql_fetch_object($result);
			mysql_free_result($result);
			if($row->cnt>=200) {
				echo "<script>alert('장바구니에는 총 200개까지만 담을수 있습니다.');</script>";
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
					// 대여 장바구니 상품 대여기간 저장
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


//장바구니 테이블 설정
$tblbasket = $basket;





// 장바구니 데이터 (Array) ==================================================
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
회원 등급 할인 메세지 ============
	RW : 금액 추가 적립
	RP  : % 추가 적립
	SW : 금액 추가 할인
	SP  : % 추가 할인
*/
$groupMemberSale = "";
if( $basketItems['groupMemberSale'] ) {
	$groupMemberSale .= "
		<font style=\"letter-spacing:0px;\"><b>회원등급할인</b> : ".$basketItems['groupMemberSale']['name']."</font>님(".$basketItems['groupMemberSale']['group'].")은
		<font color=\"#ee0a02\" style=\"letter-spacing:0px;\">".number_format($basketItems['groupMemberSale']['useMoney'])."원</font> 이상
		<font  color=\"#ee0a02\">".$basketItems['groupMemberSale']['payType']."</font> 결제시<br />
	";
	if($basketItems['groupMemberSale']['groupCode']=="RW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>".number_format($basketItems['groupMemberSale']['addMoney'])."</b>원</font>의 적립금을 추가로 적립해 드립니다.";
	} else if($basketItems['groupMemberSale']['groupCode']=="RP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>구매금액의 ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>를 적립해 드립니다.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>구매금액 ".number_format($basketItems['groupMemberSale']['addMoney'])."원</b></font>을 추가로 할인해 드립니다.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>구매금액의 ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>를 추가로 할인해 드립니다.";
	}
}

?>

<HTML>
<HEAD>
	<TITLE><?=$_data->shoptitle?> - 장바구니</TITLE>
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
						alert('선택된 항목이 없습니다.');
					}else if(confirm("선택된 항목을 장바구니에서 삭제하시겠습니까?")){
						$j.each(selitems,function(idx,el){
							itemval+= $j(el).val()+',';
						});
						$j('#selItemForm').find('input[name=mode]').val('seldel');
						$j('#selItemForm').find('input[name=basketidxs]').val(itemval);
						$j('#selItemForm').submit();
						//alert(itemval);
					}*/
					//basketForm					
				
				}else if(confirm("해당 상품을 장바구니에서 삭제하시겠습니까?")) {
					f.sbasketidx.value = idx;										
				}
			} else if(mode=="upd"){				
				if(document["form_"+idx].quantity.value.length==0 || document["form_"+idx].quantity.value==0) {
					alert("수량을 입력하세요.");
					document["form_"+idx].quantity.focus();
					return;
				}
				if(!IsNumeric(document["form_"+idx].quantity.value)) {
					alert("수량은 숫자만 입력하세요.");
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
			if(confirm("장바구니를 비우시겠습니까?")) {
				document.delform.mode.value="clear";
				document.delform.submit();
			}
		}
		function check_login() {
			if(confirm("로그인이 필요한 서비스입니다. 로그인을 하시겠습니까?")) {
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

			//Property 선언
			this.classname		= "pralllist"								//classname
			this.debug			= false;									//디버깅여부.
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
				var d = new Option("상품 선택");
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
				alert("상품을 선택하세요.");
				document.form1.tmpprcode.focus();
				return;
			}
			if(document.form1.assembleuse.value=="Y") {
				if(confirm("해당 상품은 구성상품을 구성해야만 구매가 가능한 상품입니다.\n\n         상품 상세페이지에서 구성를 하겠습니까?")) {
					location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+document.form1.productcode.value;
				}
			} else if(document.form1.package_num.value.length>0) {
				if(confirm("해당 상품은 패키지 선택 상품으로써 상품상세페이지에서 패키지 정보를 확인 해 주세요.\n\n                              상품상세페이지로 이동 하겠습니까?")) {
					location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+document.form1.productcode.value;
				}
			} else {
				document.form1.submit();
			}
		}
		<?}?>




		// 장바구니 렌탈 상품 옵션 변경
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
			echo "<td><img src=\"".$Dir.DataDir."design/basket_title.gif\" border=\"0\" alt=\"장바구니\"></td>\n";
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

	if($ordertype=="ordernow") {	//바로구매
		if($sumprice>=$_data->bank_miniprice) {
			//echo "<script>location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode($Dir.FrontDir."order.php")."';</script>";
			exit;
		} else {
			//$onload="<script>alert('".number_format($_data->bank_miniprice)."원 이상 구매가 가능합니다.(code:071)');</script>";
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
				alert(pname + " \n선물하기 불가한 상품입니다.\n해당 상품을 삭제하시고 다시한번 시도해주세요");
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
				alert(pname + " \n조르기 불가한 상품입니다.\n해당 상품을 삭제하시고 다시한번 시도해주세요");
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